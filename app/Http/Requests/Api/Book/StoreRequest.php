<?php

namespace App\Http\Requests\Api\Book;

use App\Http\Requests\Api\AbstractRequest;

class StoreRequest extends AbstractRequest
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
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'publish_date' => 'nullable|date_format:Y-m-d',
            'category_id' => 'required|numeric|exists:categories,id',
            'office_id' => 'required|numeric|exists:offices,id',
            'medias' => 'array|max:3|unique_book_image:type',
            'medias.*' => 'required|array|max:2',
            'medias.*.file' => 'required|image|mimes:jpeg,jpg,gif,bmp,png|max:10240',
            'medias.*.type' => 'required|numeric|between:0,1',
        ];
    }
}
