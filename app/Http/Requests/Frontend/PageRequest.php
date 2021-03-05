<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
            'title'        => 'required',
            'description'  => 'required|min:50',
            'status'       => 'required',
            'category_id'  => 'required',
            'images.*'     => 'nullable|mimes:png,jpg,jpeg,gif|max:20000',
        ];
    }
}
