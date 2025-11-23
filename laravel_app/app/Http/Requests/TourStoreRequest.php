<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TourStoreRequest extends FormRequest
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
            "title" => "nullable",
            "days" => "required",
            "place" => "required",
            "destination" => "required",
        ];
    }
    public function messages()
    {
        return [
            "days.required" => "日数は必須です。",
            "place.required" => "出発地は必須です。",
            "destination.required" => "目的地は必須です。",
        ];
    }
}
