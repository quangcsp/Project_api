<?php

namespace App\Http\Requests\Api\Book;

use App\Http\Requests\Api\AbstractRequest;

class BookingRequest extends AbstractRequest
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
            'item.book_id' => 'required|numeric',
            'item.owner_id' => 'required|numeric|exists:users,id',
            'item.status' => 'in:' . implode(',', array_merge(array_values(config('model.book_user.status')), [config('model.book_user_status_cancel')])),
        ];
    }
}
