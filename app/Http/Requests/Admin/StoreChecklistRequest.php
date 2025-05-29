<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreChecklistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Changed to true to allow authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:checklists,name',
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
            'items' => 'nullable|array',
            'items.*.task' => 'required_with:items|string|max:255',
            'items.*.is_required' => 'sometimes|boolean',
            'category_id' => 'nullable|exists:checklist_categories,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'frequency' => 'nullable|in:daily,weekly,monthly,quarterly,yearly',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The checklist name is required.',
            'name.unique' => 'A checklist with this name already exists.',
            'items.*.task.required_with' => 'Each checklist item must have a task description.',
            'due_date.after_or_equal' => 'The due date must be today or in the future.',
            'frequency.in' => 'Invalid frequency selected.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'items.*.task' => 'checklist item task',
            'items.*.is_required' => 'required status',
        ];
    }
}