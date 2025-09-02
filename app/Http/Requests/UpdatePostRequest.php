<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $postId = $this->route('post')->id;
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'published' => ['required', 'boolean'],
        ];
    }
    protected function prepareForValidation(): void {
        $this->merge([
            'published' => filter_var($this->input('published'), FILTER_VALIDATE_BOOL),
        ]);
    }
}
