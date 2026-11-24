<?php

namespace Tests\Feature;

use App\Models\Catatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatatanValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_requires_judul_and_isi(): void
    {
        $response = $this->from('/catatan/tambah')->post('/catatan', [
            'judul' => '',
            'isi' => '',
        ]);

        $response->assertRedirect('/catatan/tambah');
        $response->assertSessionHasErrors([
            'judul' => 'Judul wajib diisi.',
            'isi' => 'Isi catatan tidak boleh kosong.',
        ]);
    }

    public function test_store_rejects_judul_over_255_characters(): void
    {
        $response = $this->from('/catatan/tambah')->post('/catatan', [
            'judul' => str_repeat('a', 256),
            'isi' => 'Isi catatan',
        ]);

        $response->assertRedirect('/catatan/tambah');
        $response->assertSessionHasErrors([
            'judul' => 'Judul maksimal 255 karakter.',
        ]);
    }

    public function test_update_validates_and_updates_catatan(): void
    {
        $catatan = Catatan::create([
            'judul' => 'Judul lama',
            'isi' => 'Isi lama',
        ]);

        $invalidResponse = $this->from("/catatan/{$catatan->id}/edit")->put("/catatan/{$catatan->id}", [
            'judul' => '',
            'isi' => '',
        ]);

        $invalidResponse->assertRedirect("/catatan/{$catatan->id}/edit");
        $invalidResponse->assertSessionHasErrors(['judul', 'isi']);

        $validResponse = $this->put("/catatan/{$catatan->id}", [
            'judul' => 'Judul baru',
            'isi' => 'Isi baru',
        ]);

        $validResponse->assertRedirect("/catatan/{$catatan->id}");
        $validResponse->assertSessionHas('sukses', 'Catatan berhasil diperbarui!');
        $this->assertDatabaseHas('catatan', [
            'id' => $catatan->id,
            'judul' => 'Judul baru',
            'isi' => 'Isi baru',
        ]);
    }

    public function test_layout_displays_global_success_and_error_flash_messages(): void
    {
        $response = $this->withSession([
            'sukses' => 'Berhasil.',
            'error' => 'Terjadi kesalahan.',
        ])->get('/catatan/tambah');

        $response->assertOk()
            ->assertSee('Berhasil.')
            ->assertSee('Terjadi kesalahan.')
            ->assertSee('alert-success alert-dismissible')
            ->assertSee('alert-danger alert-dismissible')
            ->assertSee('bootstrap.bundle.min.js');
    }

    public function test_destroy_deletes_catatan_and_redirects_with_success_message(): void
    {
        $catatan = Catatan::create([
            'judul' => 'Catatan untuk dihapus',
            'isi' => 'Isi catatan',
        ]);

        $response = $this->delete("/catatan/{$catatan->id}");

        $response->assertRedirect('/catatan');
        $response->assertSessionHas('sukses', 'Catatan berhasil dihapus.');
        $this->assertDatabaseMissing('catatan', ['id' => $catatan->id]);
    }
}
