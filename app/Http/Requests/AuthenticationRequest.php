<?php

namespace App\Http\Requests;

class AuthenticationRequest extends BaseFormRequest
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
            'vendor_sign_up' => [
                'email'      => ['required','email',],
                'name'       => ['required','string'],
                'number'     => ['required','integer'],
                'store_name' => ['required','string'],
                'grant_type' => ['required','in:password'],
                'type'       => ['required','in:vendor'],
                'password'   => ['required_if:grant_type,password','string'],
            ],
            'vendor_login' => [
                'email'      => ['required','email',],
                'grant_type' => ['required','in:password'],
                'password'   => ['required_if:grant_type,password','string'],
            ],
            'refreshToken' => [
                'refresh_token' => "required"
            ],
            'sign_up' =>  [
                'name'           => "required",
                'number'         => "required",
                'email'          => "required|email|unique:users,email",
                'store_name'     => "required",
                'password'       => "required",
                'providers'      => "sometimes"
            ],
            'sign_in' => [
                'email' => 'required|exists:users,email',
                'password' => 'required'
            ],
            default => []
        };
    }
}
