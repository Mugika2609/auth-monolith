<?php

namespace Modules\Library\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('isbn')) {
            $this->merge([
                'isbn' => strtoupper(str_replace(['-', ' '], '', (string) $this->isbn)),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'isbn' => [
                'sometimes', 'required', 'string', 'regex:/^(\d{9}[\dX]|\d{13})$/',
                Rule::unique('books', 'isbn')->ignore($this->route('book')),
            ],
            'published_year' => ['sometimes', 'required', 'integer', 'min:1900', 'max:' . date('Y')],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'author_id' => ['sometimes', 'required', 'exists:authors,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul buku tidak boleh kosong.',
            'title.max' => 'Judul buku maksimal 255 karakter.',
            'isbn.required' => 'ISBN tidak boleh kosong.',
            'isbn.regex' => 'Format ISBN tidak valid (10 atau 13 digit).',
            'isbn.unique' => 'ISBN sudah digunakan buku lain.',
            'published_year.integer' => 'Tahun terbit harus berupa bilangan bulat.',
            'published_year.min' => 'Tahun terbit minimal 1900.',
            'published_year.max' => 'Tahun terbit tidak boleh melebihi tahun ini.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'author_id.exists' => 'Penulis yang dipilih tidak valid.',
        ];
    }
}