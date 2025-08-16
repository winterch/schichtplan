<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:60',
            'email' => 'required|email|max:100',
            'phone' => 'required|regex:/\+?[0-9\s]{8,20}/',
            'notification' => 'boolean',
            'comment' => 'max:500',
            'locale' => 'required|in:de,en,es'
        ];
    }

    /**
     * Messages for validation errors
     * @return string[]
     */
    public function messages()
    {
        return [
            'name.required' => __("validation.required.name"),
            'name.max' => __("validation.nameMax"),

            'phone.required' => __("validation.required.phone"),
            'phone.regex'  => __('validation.phoneRegex'),

            'email.required' => __("validation.required.email"),
            'email.email'  => __('validation.emailEmail'),
            'email.max'  => __('validation.emailMax'),

            'comment.max'  => __('validation.commentMax'),

            'locale.in'  => __('subscription.validLanguage'),
        ];
    }
}
