<?php

namespace App\Http\Requests\Admin\Pages;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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

        foreach ($langs as $locale) {
            $rules["name_{$locale->code}"] = ['nullable', 'string'];
        }
        $rules['image'] = [ 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];

        return $rules;
    }
}
