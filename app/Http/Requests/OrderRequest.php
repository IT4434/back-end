<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrderRequest extends FormRequest
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
        switch ($request->method()) {
            case 'POST':
                $validationRules = [
                    'shipping_address' => 'required|string|max:100',
                    'order_details' => 'required',
                    'order_details.*.quantity' => 'required|numeric|min:1',
                    'order_details.*.product_id' => 'required|exists:product_details,id',
                ];
                break;

            case 'PUT':
                $validationRules = [
                    'order_status' => 'required|string'
                ];
                break;

            default:
                $validationRules = [];
        }

        return $validationRules;
    }
}
