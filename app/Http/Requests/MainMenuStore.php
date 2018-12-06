<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainMenuStore extends BaseFormRequest
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
            'name'          => 'required|min:2|max:255|regex:/^[a-zA-Z_-]*$/' ,
            'display_name_1'  => 'required|min:2|max:255' ,
            'sort'          => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name' => 'English language is required at field (name)',
            'display_name_1' => 'English language is required at field (Display name)'
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
            'name_1' => 'trim|lowercase|escape'
        ];
    }
}
