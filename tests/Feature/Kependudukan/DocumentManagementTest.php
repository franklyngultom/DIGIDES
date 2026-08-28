<?php

namespace Tests\Feature\Kependudukan;

use App\Models\Penduduk;
use App\Models\PendudukDocument;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $staff;
    protected Penduduk $penduduk;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->staff = User::factory()->create();
        $this->staff->assignRole('Staff Desa');

        $this->penduduk = Penduduk::create([
            'nik'                   => '3202111504700001',
            'no_kk'                 => '3202110000000001',
            'nama_lengkap'          => 'Asep Suhendar',
            'tempat_lahir'          => 'Sukabumi',
            'tanggal_lahir'         => '1980-01-01',
            'jenis_kelamin'         => 'L',
            'agama'                 => 'Islam',
            'status_perkawinan'     => 'kawin',
            'status_dalam_keluarga' => 'kepala_keluarga',
            'alamat_lengkap'        => 'Kp. Cikole RT 001 RW 002',
            'rt'                    => '001',
            'rw'                    => '002',
            'sumber_data'           => 'manual',
            'status_penduduk'       => 'tetap',
        ]);
    }

    public function test_staff_can_upload_jpg_document(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('ktp.jpg', 600, 400);

        $this->actingAs($this->staff)
            ->post(route('kependudukan.documents.store', $this->penduduk), [
                'jenis_dokumen' => 'ktp',
                'file'          => $file,
            ])
            ->assertRedirect(route('kependudukan.show', $this->penduduk));

        $this->assertDatabaseHas('penduduk_documents', [
            'penduduk_id'   => $this->penduduk->id,
            'jenis_dokumen' => 'ktp',
        ]);

        $doc = PendudukDocument::where('penduduk_id', $this->penduduk->id)->first();
        Storage::disk('local')->assertExists($doc->file_path);
    }

    public function test_staff_can_upload_pdf_document(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('kk.pdf', 512, 'application/pdf');

        $this->actingAs($this->staff)
            ->post(route('kependudukan.documents.store', $this->penduduk), [
                'jenis_dokumen' => 'kk',
                'file'          => $file,
            ])
            ->assertRedirect();

        $doc = PendudukDocument::where('penduduk_id', $this->penduduk->id)->first();
        $this->assertNotNull($doc);
        $this->assertEquals('application/pdf', $doc->mime_type);
    }

    public function test_file_upload_rejects_exe_files(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload');

        $this->actingAs($this->staff)
            ->post(route('kependudukan.documents.store', $this->penduduk), [
                'jenis_dokumen' => 'ktp',
                'file'          => $file,
            ])
            ->assertSessionHasErrors('file');
    }

    public function test_file_upload_rejects_files_over_5mb(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('bigfile.jpg', 6000, 'image/jpeg'); // 6MB

        $this->actingAs($this->staff)
            ->post(route('kependudukan.documents.store', $this->penduduk), [
                'jenis_dokumen' => 'ktp',
                'file'          => $file,
            ])
            ->assertSessionHasErrors('file');
    }

    public function test_staff_can_delete_document(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('ktp.jpg');
        $path = $file->store("kependudukan/{$this->penduduk->id}/documents", 'local');

        $doc = PendudukDocument::create([
            'penduduk_id'   => $this->penduduk->id,
            'jenis_dokumen' => 'ktp',
            'nama_file'     => 'ktp.jpg',
            'file_path'     => $path,
            'file_size'     => $file->getSize(),
            'mime_type'     => 'image/jpeg',
        ]);

        Storage::disk('local')->assertExists($path);

        $this->actingAs($this->staff)
            ->delete(route('kependudukan.documents.destroy', $doc))
            ->assertRedirect(route('kependudukan.show', $this->penduduk));

        $this->assertDatabaseMissing('penduduk_documents', ['id' => $doc->id]);
        Storage::disk('local')->assertMissing($path);
    }

    public function test_document_accessors_return_correct_values(): void
    {
        $doc = PendudukDocument::create([
            'penduduk_id'   => $this->penduduk->id,
            'jenis_dokumen' => 'akta_lahir',
            'nama_file'     => 'akta.pdf',
            'file_path'     => 'test/akta.pdf',
            'file_size'     => 2048000,
            'mime_type'     => 'application/pdf',
        ]);

        $this->assertEquals('Akta Kelahiran', $doc->jenis_dokumen_label);
        $this->assertEquals('2 MB', $doc->formatted_size);
        $this->assertTrue($doc->is_pdf);
        $this->assertFalse($doc->is_image);
    }

    public function test_guest_cannot_stream_documents(): void
    {
        $doc = PendudukDocument::create([
            'penduduk_id'   => $this->penduduk->id,
            'jenis_dokumen' => 'ktp',
            'nama_file'     => 'ktp.jpg',
            'file_path'     => 'test/ktp.jpg',
            'file_size'     => 1000,
            'mime_type'     => 'image/jpeg',
        ]);

        $this->get(route('kependudukan.documents.stream', $doc))
            ->assertRedirect(route('login'));
    }
}
