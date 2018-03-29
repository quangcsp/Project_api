<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Factory as ValidationFactory;
use App\Eloquent\Category;

abstract class AbstractRequest extends FormRequest
{
    protected function formatErrors(Validator $validator)
    {
        return [
            'message' => [
                'status' => false,
                'code' => 422,
                'description' => $validator->errors()->all(),
            ]
        ];
    }

    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend(
            'unique_book_image', function ($attribute, $value, $parameters) {
                return count(array_where(array_pluck($value, $parameters), function ($value) {
                    return $value == config('model.media.type.avatar_book');
                })) <= 1;
            }, __('validation.custom.unique_book_image')
        );

        $validationFactory->extend(
            'tags_formated', function ($attribute, $value, $parameters) {
                if (!$value) {
                    return true;
                }
                
                $arrayTags = explode(',', $value);

                return app(Category::class)->whereIn('id', $arrayTags)->count() == count($arrayTags);
            }, __('validation.custom.tags_formated')
        );
    }

    public function all()
    {
        $input = parent::all();

        if (isset($input['search']) && isset($input['search']['field'])) {
            $input['search']['field'] = strtolower($input['search']['field']);
        }

        if (isset($input['sort'])) {
            if (isset($input['sort']['by'])) {
                $input['sort']['by'] = strtolower($input['sort']['by']);
            }

            if (isset($input['sort']['order_by'])) {
                $input['sort']['order_by'] = strtolower($input['sort']['order_by']);
            }
        }

        return $input;
    }
}
