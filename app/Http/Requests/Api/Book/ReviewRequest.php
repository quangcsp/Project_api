<?php

namespace App\Http\Requests\Api\Book;

use App\Http\Requests\Api\AbstractRequest;

class ReviewRequest extends AbstractRequest
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
            'item' => 'required|array|max:3',
            'item.title' => 'required|max:100',
            'item.content' => 'required',
            'item.star' => 'numeric|between:1,5',
        ];
    }
}
