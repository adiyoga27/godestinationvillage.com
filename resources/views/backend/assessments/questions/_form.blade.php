@php $question = $question ?? null; @endphp
<p class="text-muted small">Isi versi Indonesia (wajib) dan English (opsional — kosong = pengunjung English melihat versi Indonesia). Pindah bahasa lewat tombol <strong>ID / EN</strong>.</p>
@include('backend.assessments._bilingual', ['label' => 'Dimensi', 'field' => 'dimension', 'model' => $question, 'required' => true, 'hint' => 'Contoh: Pemasaran & Branding — soal dikelompokkan per dimensi. Nama English dimensi berlaku untuk semua soal di dimensi yang sama.'])
@include('backend.assessments._bilingual', ['label' => 'Pernyataan', 'field' => 'question', 'model' => $question, 'required' => true, 'type' => 'textarea', 'hint' => 'Dinilai 1–5 oleh pengisi.'])
@include('backend.assessments._bilingual', ['label' => 'Teks Bantuan', 'field' => 'help_text', 'model' => $question, 'type' => 'textarea', 'rows' => 2])
<div class="form-row">
    <div class="form-group col-md-4"><label>Bobot</label><input type="number" name="weight" value="{{ old('weight', $question->weight ?? 1) }}" class="form-control" min="1" max="10"></div>
    <div class="form-group col-md-4"><label>Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order', $question->sort_order ?? 0) }}" class="form-control" min="0"></div>
    <div class="form-group col-md-4"><label>Aktif</label><select name="is_active" class="form-control"><option value="1" {{ old('is_active', $question->is_active ?? true) ? 'selected' : '' }}>Ya</option><option value="0" {{ ! old('is_active', $question->is_active ?? true) ? 'selected' : '' }}>Tidak</option></select></div>
</div>
