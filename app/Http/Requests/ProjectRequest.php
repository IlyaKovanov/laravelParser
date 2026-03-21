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
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'base_url' =>['required', 'string', 'max:255'],
            'description' => ['string'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];

        // Если это метод update (есть ID в маршруте)
//        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
//            $projectId = $this->route('id');
//            $rules['base_url'][] = Rule::unique('projects')->ignore($projectId);
//        } else {
//            // Для создания нового проекта
//            $rules['base_url'][] = 'unique:projects';
//        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название проекта обязательный параметр',
            'name.max' => 'Название должно быть не более :max символов',
            'user_id.exists' => 'Пользователь с таким :id не найден в системе',
            'base_url.unique' => 'Проект с таким url уже существует'
        ];
    }
}
