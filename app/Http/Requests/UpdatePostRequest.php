<?php

namespace App\Http\Requests;

use App\Rules\DescriptionMaxSize;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'string',
            'description' => 'string',new DescriptionMaxSize(),
            'contact_phone' => 'nullable',
            'admin_id' => 'exists:admins,id',
        ];
    }
}
