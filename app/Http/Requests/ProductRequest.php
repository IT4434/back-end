<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
    public function rules(Request $request): array
    {
        switch ($request->method())
        {
            case 'POST':
                $validate = [
                    'product_name' => 'required|string|unique:products,product_name',
                    'brand' => 'required|string',
                    'description' => 'required|string',
                    'sold_quantity' => 'required|numeric|min:0',
                    'rating' => 'required|numeric|min:0|max:5',
                    'rating_quantity' => 'required|numeric|min:0',
                    'category_id' => 'required|exists:categories,id',
                    'images' => ' required',
                    'images.*' => 'mimes:jpeg, jpg, png|max:2048',
                ];
            break;

            case 'PUT':
                $validate = [
                    // unique:table,column,except,idColumn
                    'product_name' => 'string|unique:products,product_name,'.$this->product->id.',id',
                    'brand' => 'string',
                    'description' => 'string',
                    'sold_quantity' => 'numeric|min:0',
                    'rating' => 'numeric|min:0|max:5',
                    'rating_quantity' => 'numeric|min:0',
                    'category_id' => 'exists:categories,id',
                    'images.*' => 'mimes:jpeg, jpg, png|max:2048',
                ];
            break;

            default:
                $validate = [];
            break;
        }
        return $validate;
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
