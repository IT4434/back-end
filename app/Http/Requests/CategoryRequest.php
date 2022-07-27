<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CategoryRequest extends FormRequest
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
                $validate = [
                    'category_name' => 'required|string|unique:categories,category_name',
                    'place' => 'required|numeric',
                ];
            break;

            case 'PUT':
                $validate = [
                    // unique:table,column,except,idColumn
                    'category_name' => 'required|string|unique:categories,category_name,'.$this->category.',id',
                    'place' => 'required|numeric',
                ];
            break;

            default:
                $validate = [];
            break;
        }
        return $validate;
    }
}
