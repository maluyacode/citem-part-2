<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinalRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currentYear = date('Y');

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'username' => ['required', 'alpha_num', 'max:50', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type_of_participation' => ['required', Rule::in(['Buyer','Exhibitor','Visitor'])],

            'company.company_name' => ['required', 'string', 'max:255'],
            'company.address_line' => ['required', 'string', 'max:255'],
            'company.town_city' => ['required', 'string', 'max:255'],
            'company.region_state' => ['required', 'string', 'max:255'],
            'company.country' => ['required', 'string', 'max:255'],
            'company.year_established' => ['required', 'digits:4', 'integer', 'max:'.$currentYear],
            'company.website' => ['nullable', 'url', 'max:255'],
            'company.brochure' => ['nullable', 'file', 'max:2048', 'mimes:pdf,doc,docx'],
        ];
    }

    public function messages(): array
    {
        return [
            'company.year_established.max' => 'The year established may not be in the future.',
        ];
    }
}
