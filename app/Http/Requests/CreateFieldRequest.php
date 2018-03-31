<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\UserField;
use Auth;

class CreateFieldRequest extends FormRequest
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
        $allowedTypes = [
            UserField::TYPE_STRING,
            UserField::TYPE_DATE,
            UserField::TYPE_NUMBER,
            UserField::TYPE_BOOLEAN
        ];

        return [
            'title' => 'required|max:250',
            'type' => 'required|in:' . implode(',', $allowedTypes)
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $generatedName = strtolower(preg_replace("/[^A-Za-z0-9]/", '_', 
                request()->input('title')));

            $existingField = UserField::where('user_id', Auth::guard('api')->user()->id)
                                ->where('name', $generatedName)
                                ->first();

            if ($existingField) {
                $validator->errors()->add('title', 'Please use different title');
            }
        });
    }
}
