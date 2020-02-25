<?php

namespace App\Http\Requests;

use App\Rules\IsbnRule;

class SaveBookRequest extends ApiFormRequest
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
            'isbn' => [
                'sometimes',
                'required',
                new IsbnRule
                // 'regex:/^(97(8|9))?\d{9}(\d|X)$/i'
            ],
            'title' => [
                'sometimes',
                'required',
                'max:150'
            ],
            'year' => [
                'sometimes',
                'required',
                'integer'
            ]
        ];
    }
}
