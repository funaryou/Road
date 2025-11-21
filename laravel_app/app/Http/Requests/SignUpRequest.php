<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'icon' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ];
    }

    
    public function messages(): array
    {
        return [
            'name.required' => '名前は必須です。',
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => 'メールアドレスの形式で入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'icon.image' => 'アイコンは画像ファイルで入力してください。',
            'icon.mimes' => 'アイコンはjpeg,png,jpg,gif形式で入力してください。',
            'icon.max' => 'アイコンは10メガバイト以下で入力してください。',
            'password.required' => 'パスワードは必須です。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.confirmed' => 'パスワードとパスワード（確認）が一致しません。',
        ];
    }
}
