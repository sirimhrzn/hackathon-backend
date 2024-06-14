<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends BaseFormRequest
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
            'create_category' => [
                'name' => 'required|unique:categories,name',
                'enable' => 'required,in:y,n'
            ],
            'delete_category' => [],
            'edit_category'   => [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required',
                'active' => 'required|in:y,n'
            ],
            'update_category' => [],
            default => []
        };
    }
}
