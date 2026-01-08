<?php

namespace App\Http\Requests\Admin\Pages;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            $rules["title_{$locale->code}"] = ['required', 'string', 'max:255'];
            $rules["text_{$locale->code}"] = ['nullable', 'string'];
        }
        $rules['image'] = ['required_if:route,add','nullable', 'image', 'mimes:jpeg,png,jpg,gif'];

        return $rules;

    }
}
