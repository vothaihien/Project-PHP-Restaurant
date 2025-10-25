<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriversLicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $eighteenYearsAgo = now()->subYears(18)->format('Y-m-d');
        
        return [
            'license_number' => 'required|string|min:5|max:20',
            'reference_number' => 'required|string|min:5|max:15',
            'dob' => 'required|date|before_or_equal:' . $eighteenYearsAgo,
            'valid_on' => 'required|date|before_or_equal:today',
            'expires_on' => 'required|date|after_or_equal:today'
        ];
    }

    public function messages()
    {
        return [
            'license_number.required' => 'We need your Drivers License number.',
            'license_number.min' => 'License number must be at least 5 characters.',
            'license_number.max' => 'License number cannot exceed 20 characters.',
            'reference_number.required' => 'We need your Drivers License Reference number.',
            'reference_number.min' => 'Reference number must be at least 5 characters.',
            'reference_number.max' => 'Reference number cannot exceed 15 characters.',
            'dob.before' => 'You must be at least 18 years old.',
            'valid_on.before_or_equal' => 'The valid on date should be today or in the past.',
            'expires_on.after_or_equal' => 'The expiration date needs to be today or in the future.'
        ];
    }
}
