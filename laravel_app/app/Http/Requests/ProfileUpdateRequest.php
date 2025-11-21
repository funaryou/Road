<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . auth()->id(),
            'phone_number' => 'nullable|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ];
    }

    public function messages()
    {
        return [

            'icon.image' => 'アイコンは画像ファイルである必要があります。',
            'icon.mimes' => 'アイコンはjpeg, png, jpg, gif形式の画像ファイルである必要があります。',
            'icon.max' => 'アイコンは10MB以下の画像ファイルである必要があります。',
            'birthday.date' => '誕生日は有効な日付である必要があります。',
            'gender.in' => '性別はmale, female, otherのいずれかである必要があります。',
        ];
    }
}
