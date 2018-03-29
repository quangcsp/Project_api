<?php

namespace App\Http\Requests\Api\Book;

use App\Http\Requests\Api\AbstractRequest;

class ApproveRequest extends AbstractRequest
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
            'item.key' => 'required|in:' . implode(config('settings.book_key'), ','),
            'item.user_id' => 'required|numeric|exists:users,id',
        ];
    }
}
