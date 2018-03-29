<?php

namespace App\Http\Requests\Api\Book;

use App\Http\Requests\Api\AbstractRequest;

class BookFilterRequest extends AbstractRequest
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
            'field' => 'required|in:' . implode(',', array_keys(config('model.filter_books'))),
            'filters' => 'array',
            'filters.*' => 'array',
            'sort.by' => 'in:' . implode(',', array_pluck(config('model.condition_sort_book'), 'field')),
            'sort.order_by' => 'in:' . implode(',', config('model.sort_type')),
        ];
    }
}
