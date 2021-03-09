<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'name'    => 'required',
            'postId'  => 'required|numeric|exists:posts,id',
            'email'   => 'required|email',
            'url'     => 'nullable|url',
            'comment' => 'required|min:10',
            'status'  => 'required_with:validation',
        ];
    }
}
