<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $langs = getLangs(); // أو config('app.locales') لو عندك إعداد مسبق

        // إعداد القواعد الديناميكية
        $rules = [];

        // إضافة القواعد لكل لغة
        foreach ($langs as $locale) {
            $rules["name_{$locale->code}"] = ['required', 'string', 'max:255'];
            $rules["description_{$locale->code}"] = ['nullable', 'string'];
        }

        $rules['size'] = ['required'];
        $rules['category_id'] = ['required', 'exists:categories,id'];
        $rules['is_active'] = ['nullable', 'boolean'];
        $rules['image'] = ['required_if:route,add','nullable', 'image', 'mimes:jpeg,png,jpg,gif'];

        return $rules;
    }
}
