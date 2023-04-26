<?php

namespace App\Http\Requests\Url;

use Illuminate\Foundation\Http\FormRequest;
use Closure;

class UrlStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * We remove the trailing slash (slashed) in order to avoid unnecessary URL duplication.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'original_url' => rtrim($this->original_url, '/'),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'email'],
            'original_url' => [
                'required',
                'url',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (strlen($value) >= 16384) { // NOTE: DB (with current settings) supports up to 64 KB for this $attribute
                        $fail("The {$attribute} is longer than allowed. Max size is 16384 bytes");
                    }
                },
            ]
        ];
    }
}
