<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
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
            'alias'   => 'required|min:5|max:30',
            '_name'   => 'required|min:5|max:60',
            'link'    => 'url|nullable',
            'pass'    => 'required|confirmed',
            'des'     => 'nullable|min:5|max:400'
        ];
    }
}
