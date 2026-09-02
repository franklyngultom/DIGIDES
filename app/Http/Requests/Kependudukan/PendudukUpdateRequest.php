<?php

namespace App\Http\Requests\Kependudukan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PendudukUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('kependudukan.edit');
    }

    /**
     * Prepare inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('nik')) {
            $this->merge(['nik' => preg_replace('/[^0-9]/', '', (string) $this->nik)]);
        }
        if ($this->has('no_kk')) {
            $this->merge(['no_kk' => preg_replace('/[^0-9]/', '', (string) $this->no_kk)]);
        }
        if ($this->has('rt')) {
            $this->merge(['rt' => str_pad(preg_replace('/[^0-9]/', '', (string) $this->rt), 3, '0', STR_PAD_LEFT)]);
        }
        if ($this->has('rw')) {
            $this->merge(['rw' => str_pad(preg_replace('/[^0-9]/', '', (string) $this->rw), 3, '0', STR_PAD_LEFT)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pendudukId = $this->route('penduduk')?->id;

        return [
            'nik' => ['required', 'string', 'digits:16', Rule::unique('penduduks', 'nik')->ignore($pendudukId)],
            'no_kk' => ['required', 'string', 'digits:16'],
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'agama' => ['required', 'string', 'max:50'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:100'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'status_perkawinan' => ['required', 'in:belum_kawin,kawin,cerai_hidup,cerai_mati'],
            'status_dalam_keluarga' => ['required', 'in:kepala_keluarga,istri,anak,famili_lain,lainnya'],
            'kewarganegaraan' => ['nullable', 'string', 'max:50'],
            'golongan_darah' => ['nullable', 'string', 'max:3'],
            'alamat_lengkap' => ['required', 'string', 'max:500'],
            'rt' => ['required', 'string', 'max:3'],
            'rw' => ['required', 'string', 'max:3'],
            'dusun' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'sumber_data' => ['required', 'in:prodeskel,manual,migrasi_legacy'],
            'status_penduduk' => ['required', 'in:tetap,sementara,pindah,meninggal'],
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
            'nik' => 'NIK',
            'no_kk' => 'Nomor KK',
            'nama_lengkap' => 'Nama Lengkap',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'agama' => 'Agama',
            'pendidikan_terakhir' => 'Pendidikan Terakhir',
            'pekerjaan' => 'Pekerjaan',
            'status_perkawinan' => 'Status Perkawinan',
            'status_dalam_keluarga' => 'Status dalam Keluarga',
            'kewarganegaraan' => 'Kewarganegaraan',
            'golongan_darah' => 'Golongan Darah',
            'alamat_lengkap' => 'Alamat Lengkap',
            'rt' => 'RT',
            'rw' => 'RW',
            'dusun' => 'Dusun',
            'telepon' => 'Nomor Telepon',
            'sumber_data' => 'Sumber Data',
            'status_penduduk' => 'Status Kependudukan',
        ];
    }
}

