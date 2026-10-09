<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PelayananSuratRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('persuratan.create') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'template_id' => ['required', 'exists:surat_templates,id'],
            'penduduk_id' => ['required', 'exists:penduduks,id'],
            'keperluan'   => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'template_id.required' => 'Pilih template surat yang akan diterbitkan.',
            'penduduk_id.required' => 'Pilih penduduk pemohon surat.',
            'keperluan.required'   => 'Keperluan surat wajib diisi.',
        ];
    }
}
