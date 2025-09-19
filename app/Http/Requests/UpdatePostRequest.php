<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    // Define if the user is authorized to make this request
    // Here it always returns true → any user can send this request
    public function authorize(): bool
    {
        return true;
    }

    // Define the validation rules for updating a post
    public function rules(): array
    {
        // Get the ID of the post being updated from the route
        $postId = $this->route('post')->id;

        return [
            'title'     => ['required', 'string', 'max:255'], // required, must be string, max 255 chars
            'body'      => ['nullable', 'string'],            // optional, must be string if provided
            'published' => ['required', 'boolean'],           // required, must be boolean
        ];
    }

    // Prepare input before validation
    // Ensures "published" is always cast to a true boolean (true/false)
    protected function prepareForValidation(): void {
        $this->merge([
            'published' => filter_var($this->input('published'), FILTER_VALIDATE_BOOL),
        ]);
    }
}
