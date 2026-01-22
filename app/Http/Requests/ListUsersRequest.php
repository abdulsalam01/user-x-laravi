<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Route already protected by auth:sanctum.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'sortBy' => ['nullable', 'in:name,email,created_at'],
            'perPage' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function perPage(): int
    {
        return (int) ($this->input('perPage') ?: 5);
    }

    public function sortBy(): string
    {
        return (string) ($this->input('sortBy') ?: 'created_at');
    }

    public function search(): string
    {
        return trim((string) ($this->input('search') ?: ''));
    }
}
