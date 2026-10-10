<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        if ($this->has('links') && is_string($this->links)) {
            $this->merge([
                'links' => collect(explode("\n", $this->links))
                    ->map(fn ($line) => trim($line))
                    ->filter()
                    ->unique()
                    ->values()
                    ->map(fn ($url) => ['url' => $url])
                    ->all(),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'base_url'    => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'links'         => ['nullable', 'array'],
            'links.*.url'   => ['required', 'url'],

            'selectors' => ['nullable', 'array'],
            'selectors.*.title'            => ['required_with:selectors', 'string'],
            'selectors.*.selector'         => ['required_with:selectors', 'string'],
            'selectors.*.selector_type_id' => ['required_with:selectors', 'exists:selector_types,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название проекта обязательный параметр',
            'name.max' => 'Название должно быть не более :max символов',
            'base_url.unique' => 'Проект с таким url уже существует'
        ];
    }
}
