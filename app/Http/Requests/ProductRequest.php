<?php

namespace App\Http\Requests;

use App\Enums\ExceptionEnum;
use App\Exceptions\CustomException;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends BaseFormRequest
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
        $action = explode('@', $this->route()->getActionName())[1];
        $this->merge([
            'product_id' => $this->route('product_id') ?? $this->all('product_id') ?? null
        ]);
        $common_rules = [];
        $rules = match ($action) {
            "create_product" => $this->get_create_product_rules(),
            "update_product" => $this->get_update_product_rules(),
            default => []
        };
        return $rules;
    }
    private function get_create_product_rules(): array
    {
        return [
                "name"                              => "required|string",
                "enabled"                           => "required|in:y,n",
                "category_id"                       => "required|integer|exists:categories,id",
                "metadata.description"              => "sometimes|string",
                "data.tax_amount"                   => "sometimes|float",
                "data.product_service_charge"       => "sometimes|float",
                "image1"                            => "required|image|mimes:jpeg,png,jpg,gif,svg",
                "image2"                            => "sometimes|image|mimes:jpeg,png,jpg,gif,svg",
                "image3"                            => "sometimes|image|mimes:jpeg,png,jpg,gif,svg",
                "image4"                            => "sometimes|image|mimes:jpeg,png,jpg,gif,svg",
        ];
    }
    private function get_update_product_rules(): array
    {
       return [
                'product_id'                        => 'required|integer|exists:products,id',
                "name"                              => "sometimes|string",
                "enabled"                           => "sometimes|in:y,n",
                "category_id"                       => "sometimes|integer|exists:categories,id",
                "metadata.description"              => "sometimes|string",
                "data.tax_amount"                   => "sometimes|float",
                "data.product_service_charge"       => "sometimes|float",
                "image1"                            => "sometimes|image|mimes:jpeg,png,jpg,gif,svg",
                "image2"                            => "sometimes|image|mimes:jpeg,png,jpg,gif,svg",
                "image3"                            => "sometimes|image|mimes:jpeg,png,jpg,gif,svg",
                "image4"                            => "sometimes|image|mimes:jpeg,png,jpg,gif,svg",

        ];
    }

    public function messages()
    {
        return [
            'metadata.description' => 'Product description is required',
        ];
    }

}
