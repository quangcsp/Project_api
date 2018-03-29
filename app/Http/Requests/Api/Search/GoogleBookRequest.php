<?php

namespace App\Http\Requests\Api\Search;

use App\Http\Requests\Api\AbstractRequest;

class GoogleBookRequest extends AbstractRequest
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
            'title' => 'nullable|string|max:100',
            'inauthor' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:100',
            'q' => 'required|string|max:100',
            'maxResults' => 'integer|max:40',
        ];
    }
}
