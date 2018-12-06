<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeywordStore extends FormRequest
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
            'name'  => 'required|min:2|max:255|unique:languages,name|regex:/^[a-zA-Z0-9_-]*$/' ,
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Name of Keyword has already been found',
            'name.required' => 'Name of Keyword is (Required)',
            'name.regex' => 'Invalid format : you could only type (a-z) letters , numbers and (-,_) characters',
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'name' => 'trim|lowercase|escape'
        ];
    }
}
