<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiaryRequest extends FormRequest
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
        return [
            'date' => ['required', 'date'],
            'yesterday_sleep_time' => ['required', 'date_format:H:i'],
            'today_wakeup_time' => ['required', 'date_format:H:i'],
            'text' => ['required', 'string'],
            'urgent_important' => ['required', 'string'],
            'urgent_notimportant' => ['required', 'string'],
            'noturgent_important' => ['required', 'string'],
            'noturgent_notimportant' => ['required', 'string'],
            'good_memo' => ['required', 'string'],
            'sleep' => ['required', 'integer', 'min:0', 'max:10'],
            'management' => ['required', 'integer', 'min:0', 'max:10'],
            'utilized' => ['required', 'integer', 'min:0', 'max:10'],
            'wasted' => ['required', 'integer', 'min:0', 'max:10'],
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
            'yesterday_sleep_time.date_format' => 'Sleep wake time must be in HH:MM format.',
            'today_wakeup_time.date_format' => 'Sleep wake time must be in HH:MM format.',
            'sleep.min' => 'Sleep hours cannot be negative.',
            'sleep.max' => 'Sleep hours cannot exceed 24.',
            'management.min' => 'Management time cannot be negative.',
            'utilized.min' => 'Utilized time cannot be negative.',
            'wasted.min' => 'Wasted time cannot be negative.',
        ];
    }
}
