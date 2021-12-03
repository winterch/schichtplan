<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
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
        return [
            'title' => 'required|max:200',
            'description' => 'required|max:500',
            'contact' => 'max:500',
            'owner_email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    /**
     * Messages for validation errors
     * @return string[]
     */
    public function messages()
    {
        return [
            'title.required' => "Title is required",
            'description.required'  => "Description is required",
            'contact.max'  => "Max length of contact is 500 characters",
            'owner_email.required'  => "Email is required",
            'password.required'  => "Password is required",
        ];
    }
}
