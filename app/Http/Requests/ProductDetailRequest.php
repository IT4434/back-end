<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request): array
    {
        switch ($request->method())
        {
            case 'POST':
                $validate = [
                    'product_id' => 'required|exists:products,id',
                    'price' => 'required|min:0|max:999999999999',
                    'available_quantity' => 'required|min:0',
                    'sale' => 'required|min:0|lt:price',
                    'color' => 'required|string',
                    'images' => 'required',
                    'images.*' => 'mimes:jpeg, jpg, png|max:2048',
                ];
            break;

            case 'PUT':
                $validate = [
                    'product_id' => 'required|exists:products,id',
                    'price' => 'required|min:0|max:999999999999',
                    'available_quantity' => 'required|min:0',
                    'sale' => 'required|min:0|lt:price',
                    'color' => 'required|string',
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
