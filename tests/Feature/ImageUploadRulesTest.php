<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Upload gambar di seluruh situs: hanya JPG/JPEG/PNG/WEBP, maks. 5 MB. */
class ImageUploadRulesTest extends TestCase
{
    private function upload(UploadedFile $file)
    {
        $admin = User::where('role_id', 1)->firstOrFail();

        return $this->actingAs($admin)->post(route('tinymce.upload_image'), ['file' => $file]);
    }

    public function test_allowed_image_formats_under_five_mb_are_accepted(): void
    {
        Storage::fake('public');

        foreach (['a.jpg', 'b.jpeg', 'c.png', 'd.webp'] as $name) {
            $this->upload(UploadedFile::fake()->image($name, 40, 40)->size(1500))
                ->assertOk()->assertJsonStructure(['location']);
        }
    }

    public function test_other_image_formats_are_rejected(): void
    {
        Storage::fake('public');

        foreach (['a.gif', 'b.bmp'] as $name) {
            $this->upload(UploadedFile::fake()->image($name, 40, 40))
                ->assertStatus(422)->assertJsonPath('error.message', 'Format gambar harus JPG, JPEG, PNG, atau WEBP.');
        }
        $this->upload(UploadedFile::fake()->create('shell.php', 1, 'application/x-php'))->assertStatus(422);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_images_over_five_mb_are_rejected(): void
    {
        Storage::fake('public');

        $this->upload(UploadedFile::fake()->image('big.jpg', 40, 40)->size(5121))
            ->assertStatus(422)->assertJsonPath('error.message', 'Ukuran gambar maksimal 5 MB.');
    }

    public function test_form_upload_returns_field_error_and_documents_are_untouched(): void
    {
        Storage::fake('public');
        $base = [
            'village_name' => 'Desa Upload Test', 'contact_name' => 'X', 'email' => 'upload@example.com',
            'phone' => '0812', 'address' => 'Jl. Test',
        ];

        $this->post(route('village-submission.store'), $base + ['attachment' => UploadedFile::fake()->image('a.gif')])
            ->assertSessionHasErrors(['attachment' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.'])
            ->assertSessionHas('error');

        // PDF bukan gambar: batas gambar tidak berlaku (aturan form sendiri: maks 5 MB).
        $this->post(route('village-submission.store'), ['attachment' => UploadedFile::fake()->create('doc.pdf', 3000, 'application/pdf')])
            ->assertSessionDoesntHaveErrors(['attachment']);
    }
}
