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

            'icon.image' => 'Icon must be an image file.',
            'icon.mimes' => 'Icon must be a file of type: jpeg, png, jpg, gif.',
            'icon.max' => 'Icon must not exceed 10MB.',
            'birthday.date' => 'Birthday must be a valid date.',
            'gender.in' => 'Gender must be one of: male, female, other.',
        ];
    }
}
