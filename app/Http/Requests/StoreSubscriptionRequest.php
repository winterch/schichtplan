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
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|regex:/[0-9\s]{10,15}/',
            'notification' => 'boolean',
            'comment' => 'max:500',
        ];
    }

    /**
     * Messages for validation errors
     * @return string[]
     */
    public function messages()
    {
        return [
            'name.required' => __("subscription.nameRequired"),
            'email.required'  => __('subscription.emailRequired'),
            'phone.regex'  => __('subscription.phoneRegex'),
            'comment.max'  => __('subscription.commentMax'),
        ];
    }
}
