<?php

namespace App\Http\Requests;

use App\Models\Diary;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @method \App\Models\User|null user($guard = null)
 * @method mixed route($param = null, $default = null)
 */
class UpdateDiaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Diary $diary */
        $diary = $this->route('diary');

        return $diary && $this->user() && $this->user()->can('update', $diary);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'sleep_wake_time' => ['required', 'date_format:H:i'],
            'text' => ['required', 'string'],
            'urgent_important' => ['required', 'string'],
            'urgent_notimportant' => ['required', 'string'],
            'noturgent_important' => ['required', 'string'],
            'noturgent_notimportant' => ['required', 'string'],
            'good_memo' => ['required', 'string'],
            'sleep' => ['required', 'integer', 'min:0', 'max:24'],
            'management' => ['required', 'integer', 'min:0'],
            'utilized' => ['required', 'integer', 'min:0'],
            'wasted' => ['required', 'integer', 'min:0'],
            'checklist_array' => ['required', 'string'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.date' => 'Please provide a valid date.',
            'sleep_wake_time.date_format' => 'Sleep wake time must be in HH:MM format.',
            'sleep.min' => 'Sleep hours cannot be negative.',
            'sleep.max' => 'Sleep hours cannot exceed 24.',
            'management.min' => 'Management time cannot be negative.',
            'utilized.min' => 'Utilized time cannot be negative.',
            'wasted.min' => 'Wasted time cannot be negative.',
        ];
    }
}
