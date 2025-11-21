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
            'files.*' => 'required|file|max:10240',
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
            'content.required' => '投稿内容は必須です。',
            'content.string' => '投稿内容は文字列で入力してください。',
            'content.max' => '投稿内容は1000文字以内で入力してください。',
            'files.*.required' => 'ファイルは必須です。',
            'files.*.file' => '有効なファイルをアップロードしてください。',
            'files.*.max' => 'ファイルサイズは10MBを超えてはいけません。',
        ];
    }
    
}
