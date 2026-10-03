<?php

namespace App\Services;

use App\Jobs\GenerateAssessmentReport;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Validasi profil & penyimpanan hasil asesmen — dipakai alur guest dan input manual admin.
 */
class AssessmentSubmissionService
{
    /**
     * Aturan validasi profil sesuai jalur.
     *
     * @return array{0: array, 1: array, 2: array} [rules, messages, attributes]
     */
    public static function profileRules(AssessmentTrack $track): array
    {
        $profile = AssessmentService::profileFor($track);
        $business = $profile['business_fields'];

        $rules = [
            'organization' => 'required|string|max:191',
            'institution' => [$profile['destination_fields'] ? 'required' : 'nullable', 'string', 'max:191'],
            'business_type' => [
                $business || $profile['entity_field'] ? 'required' : 'nullable',
                Rule::in($profile['entity_field'] ? AssessmentService::ENTITY_TYPES : AssessmentService::BUSINESS_TYPES),
            ],
            'business_sector' => [$business ? 'required' : 'nullable', Rule::in(AssessmentService::BUSINESS_SECTORS)],
            'member_count' => 'nullable|integer|min:1|max:1000000',
            'province' => 'required|string|max:191',
            'regency' => 'required|string|max:191',
            'district' => 'nullable|string|max:191',
            'subdistrict' => 'nullable|string|max:191',
            'postal_code' => 'nullable|string|max:10',
            'name' => 'required|string|max:191',
            'phone' => 'required|string|max:50',
            'email' => 'required|email:rfc|max:191',
            'profile_description' => 'nullable|string|max:3000',
        ];

        $messages = [
            'province.required' => 'Pilih lokasi dari hasil pencarian '.$profile['location_label'].'.',
            'regency.required' => 'Pilih lokasi dari hasil pencarian '.$profile['location_label'].'.',
        ];

        $attributes = [
            'organization' => $profile['organization_label'],
            'institution' => 'Instansi/OPD Pengusul',
            'business_type' => $profile['entity_field'] ? 'Jenis Entitas' : 'Jenis Badan Usaha',
            'business_sector' => 'Sektor Usaha',
            'member_count' => 'Jumlah Anggota/Pelaku Usaha',
            'name' => 'Nama Kontak',
            'phone' => $profile['phone_label'],
            'email' => 'Email',
            'profile_description' => $profile['description_label'],
        ];

        return [$rules, $messages, $attributes];
    }

    /**
     * Hitung skor & simpan hasil. Mengembalikan null bila ada pernyataan yang belum dijawab.
     *
     * @param  array<string, mixed>  $identity  data profil tervalidasi
     * @param  array<int|string, mixed>  $answersInput  [question_id => 1..5]
     * @param  array<int|string, mixed>  $notesInput  [question_id pertama tiap dimensi => catatan]
     * @param  array<string, mixed>  $extra  kolom tambahan (source, created_by, is_unlocked, ...)
     */
    public static function store(AssessmentTrack $track, array $identity, array $answersInput, array $notesInput, array $extra = []): ?AssessmentResult
    {
        $questions = $track->activeQuestions()->get();
        $validIds = $questions->pluck('id')->all();
        $answers = collect($answersInput)
            ->only($validIds)
            ->map(fn ($v) => (int) $v)
            ->all();

        if (count($answers) !== count($validIds)) {
            return null;
        }

        // Catatan dikirim per pertanyaan pertama tiap dimensi → simpan sebagai [dimensi => catatan].
        $dimensionByQuestion = $questions->pluck('dimension', 'id');
        $notes = collect($notesInput)
            ->filter(fn ($note, $id) => isset($dimensionByQuestion[$id]) && trim((string) $note) !== '')
            ->mapWithKeys(fn ($note, $id) => [$dimensionByQuestion[$id] => trim($note)])
            ->all();

        $computed = AssessmentService::compute($track, $questions, $answers);
        // Jalur gratis (harga 0) langsung terbuka; selain itu menunggu pembayaran, kecuali ditentukan lain.
        $unlocked = $extra['is_unlocked'] ?? ((int) $track->price === 0);

        $result = AssessmentResult::create(array_merge([
            'uuid' => (string) Str::uuid(),
            'track_id' => $track->id,
            'name' => $identity['name'],
            'phone' => isset($identity['phone']) ? AssessmentPaymentService::normalizePhone($identity['phone']) : null,
            'email' => $identity['email'] ?? null,
            'organization' => $identity['organization'] ?? null,
            'institution' => $identity['institution'] ?? null,
            'business_type' => $identity['business_type'] ?? null,
            'business_sector' => $identity['business_sector'] ?? null,
            'member_count' => $identity['member_count'] ?? null,
            'province' => $identity['province'] ?? null,
            'regency' => $identity['regency'] ?? null,
            'district' => $identity['district'] ?? null,
            'subdistrict' => $identity['subdistrict'] ?? null,
            'postal_code' => $identity['postal_code'] ?? null,
            'profile_description' => $identity['profile_description'] ?? null,
            'answers' => $answers,
            'dimension_scores' => $computed['dimensions'],
            'dimension_notes' => $notes ?: null,
            'total_score' => $computed['total'],
            'band' => $computed['band'],
            // Arsip lengkap hasil hitung (kekuatan, tantangan, aksi prioritas, subindeks TTDI, dll).
            'computed_result' => $computed,
            'status' => 'baru',
        ], $extra, [
            'is_unlocked' => $unlocked,
            'unlocked_at' => $unlocked ? now() : null,
        ]));

        if ($unlocked) {
            GenerateAssessmentReport::dispatch($result->uuid)->afterCommit();
        }

        return $result;
    }
}
