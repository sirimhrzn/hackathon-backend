<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends BaseFormRequest
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
        return  match ($action) {
            'place_order' => [
                'product'
            ],
            'update_order' => [
                "order_id" => "required|exists:orders,id",
                "location_id" => "sometimes",
                "order_details" => "sometimes",
                "payment_method_id" => "sometimes",
                "payment_status" => "sometimes",
                "order_status" => "sometimes",
                // "payment_identifier" => "sometimes",
                // "tid" => "sometimes"
            ],
            default => []
        };
    }
}
