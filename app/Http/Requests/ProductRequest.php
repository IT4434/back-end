<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'product_name' => 'required|string|unique:products',
            'brand' => 'required|string',
//            'sale' => 'required|min:0|max:100|numeric',
            'description' => 'required|string',
            'sold_quantity' => 'required|numeric|min:0',
            'rating' => 'required|numeric|min:0|max:5',
            'rating_quantity' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
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
