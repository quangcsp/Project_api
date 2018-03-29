<?php

namespace App\Http\Requests\Api\Book;

use App\Http\Requests\Api\AbstractRequest;

class UploadMediaRequest extends AbstractRequest
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
            'book_id' => 'required|exists:books,id',
            'medias' => 'required|array|unique_book_image:type',
            'medias.*' => 'required|array|max:2',
            'medias.*.file' => 'required|image|mimes:jpeg,jpg,gif,bmp,png|max:10240',
            'medias.*.type' => 'required|numeric|between:0,1',
        ];
    }
}
