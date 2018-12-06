<?php

namespace App\Http\Requests;


class CategoryStoreRequest extends BaseFormRequest
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
            'mm_parent_id'  => 'required_if:cat_parent_id,' ,
            ///'cat_parent_id' => 'required_if:mm_parent_id,' ,
            'name'          => 'required|min:2|max:255|regex:/^[a-zA-Z0-9_-]*$/' ,
            'display_name_1'  => 'required|min:2|max:255' ,
            'sort'          => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'mm_parent_id.required_if' => 'the new category must have at least one category belong to it !!' ,
            'name.required' => 'The Actual Name field is required.' ,
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
            'name' => 'trim|lowercase|escape'
        ];
    }

}
