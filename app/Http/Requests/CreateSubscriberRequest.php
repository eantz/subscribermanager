<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use App\Subscriber;
use App\UserField;
use App\Libraries\VerifyEmail;

class CreateSubscriberRequest extends FormRequest
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
        $fields = UserField::whereNull('user_id')
                    ->orWhere('user_id', Auth::guard('api')->user()->id)
                    ->get();

        $rules = [
            'email' => 'required|email|max:190',
            'name'  => 'required|max:190'
        ];

        foreach ($fields as $field) {
            if (!in_array($field->name, ['email', 'name'])) {
                $rules[$field->name] = 'nullable|max:250';

                if ($field->type == UserField::TYPE_DATE) {
                    $rules[$field->name] .= '|date_format:Y-m-d';
                } elseif ($field->type == UserField::TYPE_NUMBER) {
                    $rules[$field->name] .= '|numeric';
                } elseif ($field->type == UserField::TYPE_BOOLEAN) {
                    $rules[$field->name] .= '|boolean';
                }
            }
        }


        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = request()->input('email');

            if($email != '') {
                // check email active
                $verifier = new VerifyEmail(['mx_validation_only'=>true]);

                if (!$verifier->validate($email)) {
                    $validator->errors()->add('email', 'Email does not exist');
                }

                // check email already subscribed
                $subscriberExist = Subscriber::where('email', $email)
                                        ->first();

                if ($subscriberExist) {
                    $validator->errors()->add('email', 'Email already subscribed');
                }
            }


        });
    }
}
