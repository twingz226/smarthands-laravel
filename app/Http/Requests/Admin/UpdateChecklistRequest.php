<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChecklistRequest extends FormRequest
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
        $checklistId = $this->route('checklist'); // Get the checklist ID from route

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('checklists', 'name')->ignore($checklistId)
            ],
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
            'items' => 'nullable|array',
            'items.*.id' => 'sometimes|exists:checklist_items,id',
            'items.*.task' => 'required_with:items|string|max:255',
            'items.*.is_required' => 'sometimes|boolean',
            'items.*.is_completed' => 'sometimes|boolean',
            'category_id' => 'nullable|exists:checklist_categories,id',
            'due_date' => 'nullable|date',
            'frequency' => 'nullable|in:daily,weekly,monthly,quarterly,yearly',
            'delete_items' => 'nullable|array',
            'delete_items.*' => 'exists:checklist_items,id',
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
            'frequency.in' => 'Invalid frequency selected.',
            'items.*.id.exists' => 'One or more checklist items do not exist.',
            'delete_items.*.exists' => 'One or more items to delete do not exist.',
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