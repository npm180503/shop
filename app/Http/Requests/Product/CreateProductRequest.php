<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
        return [
            'name' => 'required',
            'thumb' => 'required|image|mimes:jpeg, png, jpg, gif|max:2048',
            'price' => 'required',
            'description' => 'required',
            'content' => 'required',
            'menu_id' => 'required'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            "price" => str_replace('.', '', $this->input('price')),
            "price_sale" => $this->input('price_sale') ? str_replace('.', '', $this->input('price_sale')) : null
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống.',
        ];
    }
}
