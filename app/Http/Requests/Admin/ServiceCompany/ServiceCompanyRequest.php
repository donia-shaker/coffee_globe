<?php

namespace App\Http\Requests\Admin\ServiceCompany;

use Illuminate\Foundation\Http\FormRequest;

class ServiceCompanyRequest extends FormRequest
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
        $langs = getLangs();

        $rules = [];

        foreach ($langs as $locale) {
            $rules["name_{$locale->code}"] = ['required', 'string', 'max:255'];
            $rules["text_{$locale->code}"] = ['required', 'string'];
        }

        return $rules;
    }
}
