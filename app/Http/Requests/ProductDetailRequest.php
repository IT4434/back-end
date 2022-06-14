<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'price' => 'required|min:0|max:999999999999',
            'available_quantity' => 'required|min:0',
            'sale' => 'required|min:0|lt:price',
            'color' => 'required|string',
            'images' => 'required',
            'images.*' => 'mimes:jpeg, jpg, png|max:2048',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'images.*.mimes' => 'The images must be a file of type: jpeg, jpg, png.',
        ];
    }
}
