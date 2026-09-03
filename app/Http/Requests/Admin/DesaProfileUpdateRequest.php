<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DesaProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('desa.update') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_desa' => ['required', 'string', 'max:150'],
            'kode_desa' => ['nullable', 'string', 'max:50'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'alamat_kantor' => ['required', 'string'],
            'email_desa' => ['nullable', 'email', 'max:100'],
            'telepon_desa' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:150'],
            'nama_kades' => ['required', 'string', 'max:150'],
            'nip_kades' => ['nullable', 'string', 'max:50'],
            'nik_kades' => ['nullable', 'string', 'size:16'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'foto_desa' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'hapus_foto_desa' => ['nullable', 'boolean'],
        ];
    }
}
