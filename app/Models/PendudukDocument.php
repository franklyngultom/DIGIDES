<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendudukDocument extends Model
{
    use HasFactory;

    protected $table = 'penduduk_documents';

    protected $fillable = [
        'penduduk_id',
        'jenis_dokumen',
        'nama_file',
        'file_path',
        'file_size',
        'mime_type',
    ];

    /**
     * Relations.
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    /**
     * Label for document type.
     */
    protected function jenisDokumenLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->jenis_dokumen) {
                'ktp' => 'KTP',
                'kk' => 'Kartu Keluarga',
                'akta_lahir' => 'Akta Kelahiran',
                'surat_nikah' => 'Surat Nikah',
                'ijazah' => 'Ijazah',
                default => 'Dokumen Lainnya',
            }
        );
    }

    /**
     * Accessible URL for secure file download.
     */
    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('kependudukan.documents.stream', $this->id)
        );
    }

    /**
     * Human-readable file size.
     */
    protected function formattedSize(): Attribute
    {
        return Attribute::make(
            get: function () {
                $bytes = (int) $this->file_size;
                if ($bytes >= 1048576) {
                    return round($bytes / 1048576, 1).' MB';
                }
                if ($bytes >= 1024) {
                    return round($bytes / 1024, 1).' KB';
                }

                return $bytes.' B';
            }
        );
    }

    /**
     * Whether file is an image.
     */
    protected function isImage(): Attribute
    {
        return Attribute::make(
            get: fn () => str_starts_with((string) $this->mime_type, 'image/')
        );
    }

    /**
     * Whether file is a PDF.
     */
    protected function isPdf(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->mime_type === 'application/pdf'
        );
    }
}
