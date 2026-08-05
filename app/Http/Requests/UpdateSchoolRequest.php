<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('school.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $school = $this->route('school');

        return [
            'name' => 'required|max:255',
            'code' => ['required', Rule::unique('schools', 'code')->ignore($school->id)],
            'email' => ['required', 'email', Rule::unique('schools', 'email')->ignore($school->id)],
            'phone' => 'nullable|max:20',
            'address' => 'nullable',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'principal_name' => 'nullable|max:255',
            'logo' => 'nullable|image|max:2048',
            'status' => 'required',
        ];
    }
}
