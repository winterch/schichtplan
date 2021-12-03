<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
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
            'type' => 'required|max:200',
            'description' => 'max:500',
            'group' => 'int|numeric|max:100|nullable',
            'start' => 'required|date|before:end',
            'end' => 'required|date|after:start',
            'team_size' => 'required|int|max:100|numeric',
        ];
    }
}
