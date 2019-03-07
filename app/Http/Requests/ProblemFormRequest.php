<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitFormRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content_md' => 'required|string|max:65000'
            //
        ];
    }

    public function message() {
        return [
            'title.required' => "Title can't be empty",
            'title.max' => "Title is too long",
            'content_md.max' => "Content is to long"
        ]
    }
}