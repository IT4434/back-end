<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:100|unique:users',
            'name' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
            'address' => 'required|string',
            'phone' => 'required|string|between:10,12',
        ];
    }
}
