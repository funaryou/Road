<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string|max:1000',
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,mp4,mov,avi,wmv|max:10240',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'Content is required.',
            'content.string' => 'Content must be a string.',
            'content.max' => 'Content must not exceed 1000 characters.',
            'files.*.required' => 'File is required.',
            'files.*.file' => 'Please upload a valid file.',
            'files.*.mimes' => 'The file must be a file of type: jpeg, png, jpg, gif, svg, webp, mp4, mov, avi, wmv.',
            'files.*.max' => 'File size must not exceed 10MB.',
        ];
    }
    
}
