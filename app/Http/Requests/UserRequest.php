<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user'); // Get user ID for update scenario

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email' . ($userId ? ",$userId" : '') // Unique email for both create and update
            ],
            'role' => ['required', 'in:manager,user'], 
            'password' => [
                $userId ? 'nullable' : 'required', // Required for create, optional for update
                'string',
                'min:3',
                'confirmed' // Ensures password_confirmation matches
            ],
        ];
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'role.required' => 'Please select a valid role.',
            'password.required' => 'The password field is required.',
            'password.min' => 'Password must be at least 3 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
