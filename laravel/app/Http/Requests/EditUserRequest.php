<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:32'],
            'city' => ['required', 'string', 'max:32'],
            'address' => ['nullable', 'string', 'max:32'],
            'job' => ['nullable', 'string', 'max:32'],
            'self_introduce' => ['nullable', 'string', 'max:512'],
        ];
    }
}
