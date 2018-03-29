<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\AbstractRequest;

class AddTagsRequest extends AbstractRequest
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
            'item' => 'required|array|max:1',
            'item.tags' => 'tags_formated',
        ];
    }
}
