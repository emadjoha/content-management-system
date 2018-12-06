<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStore extends BaseFormRequest 
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
            'cat_id'  => 'required' ,
            'description_1' => 'required|min:3|max:180' ,
            'name'          => 'required|min:2|max:255|regex:/^[a-zA-Z0-9_-]*$/' ,
            'content_1'     => 'required' ,
            'title_1'       => 'required' ,
            'pic'           => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:40000' 
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'The Actual Name can contain letter , digit , and ( - , _ ) characters ' ,
            'description_1.required' => 'At least you have to type english language for (Description) ' ,
            'content_1.required' => 'At least you have to type english language for (Content) ' 
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
