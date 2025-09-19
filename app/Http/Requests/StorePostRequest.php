<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Define if the user is authorized to make this request
    // Here it always returns true → any user can send this request
    public function authorize(): bool {
        return true;
    }

    // Define the validation rules for incoming data
    public function rules(): array
    {
        return [
            'title'     => ['required','string','max:255'], // title is required, must be a string, max length 255
            'body'      => ['nullable','string'],           // body is optional, must be a string if present
            'published' => ['required','boolean'],          // published is required and must be boolean
        ];
    }

    // Custom error messages for validation
    public function messages(): array {
        return [
            'title.required' => 'Title is required',
            'title.max'      => 'Maximum 255 characters allowed',
        ];
    }

    // Prepare input data before validation
    // Here, "published" is normalized into a real boolean (true/false)
    protected function prepareForValidation(): void {
        $this->merge([
            'published' => filter_var($this->input('published'), FILTER_VALIDATE_BOOL),
        ]);
    }
}
