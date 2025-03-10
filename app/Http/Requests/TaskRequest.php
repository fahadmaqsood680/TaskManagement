<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class TaskRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tasks', 'title')->ignore($this->task),
            ],
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date|after_or_equal:today',
            'category_id' => 'required|exists:categories,id',
            'assigned_to' => 'required|exists:users,id',
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title must not exceed 255 characters.',

            'description.string' => 'The description must be a valid text.',

            'priority.required' => 'Please select a priority.',
            'priority.in' => 'The priority must be one of the following: low, medium, or high.',

            'due_date.required' => 'The due date field is required.',
            'due_date.date' => 'Please enter a valid date.',
            'due_date.after_or_equal' => 'The due date cannot be before today.',

            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',

            'assigned_to.required' => 'Please select a user.',
            'assigned_to.exists' => 'The selected user is invalid.',
        ];
    }
}
