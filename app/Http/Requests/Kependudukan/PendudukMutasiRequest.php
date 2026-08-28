<?php

namespace App\Http\Requests\Kependudukan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PendudukMutasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('kependudukan.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis_mutasi' => ['required', 'in:lahir,mati,pindah_keluar,pindah_masuk'],
            'tanggal_mutasi' => ['required', 'date', 'before_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'berkas_pendukung' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * Custom attribute names.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'jenis_mutasi' => 'Jenis Mutasi',
            'tanggal_mutasi' => 'Tanggal Mutasi',
            'keterangan' => 'Keterangan',
            'berkas_pendukung' => 'Berkas Pendukung',
        ];
    }
}
