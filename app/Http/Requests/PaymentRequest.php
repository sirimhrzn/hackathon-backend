<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends BaseFormRequest
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
        return match ($action) {
            'initiateKhalti' => [
                'name' => 'required',
                'number' => 'required',
                'location_id' => 'required',
                'additional' => 'sometimes',
                'orders' => 'required|array'
            ],
            default => []
        };
    }
}
