<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\UserField;
use Auth;

class DeleteFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $field = UserField::find($this->route('id'));

        return $field && $field->user_id == Auth::guard('api')->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
