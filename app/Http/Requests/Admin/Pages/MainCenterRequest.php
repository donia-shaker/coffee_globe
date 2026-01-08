<?php

namespace App\Http\Requests\Admin\Pages;

use Illuminate\Foundation\Http\FormRequest;

class MainCenterRequest extends FormRequest
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

            'name_ar' => 'required',
            'name_en' => 'required',
            'address_ar' => 'required',
            'address_en' => 'required',
            'location' => 'nullable|string|max:1000',
            'phone' => 'required|string|max:20', // ممكن تضيف regex إذا تحب تتأكد من صيغة الرقم
            'email' => 'required|email|max:255',
            'website' => 'required|max:255',
            'support' => 'required|string|max:255',
        ];
    }
}
