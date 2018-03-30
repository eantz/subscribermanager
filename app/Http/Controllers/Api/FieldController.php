<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserField;
use Auth;
use App\Transformers\FieldTransformer;
use App\Http\Requests\CreateFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Http\Requests\DeleteFieldRequest;

class FieldController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::guard('api')->user();

        $fields = UserField::where('user_id', $user->id)
                    ->get();

        $fields = fractal($fields, new FieldTransformer())->toArray();

        return response()->json(['fields' => $fields['data']]);
    }

    public function create(CreateFieldRequest $request)
    {
        $validated = $request->validated();

        $field = new UserField;
        $field->user_id = Auth::guard('api')->user()->id;
        $field->title = $validated['title'];
        $field->type = $validated['type'];
        $field->name = strtolower(preg_replace("/[^A-Za-z0-9]/", '_', $validated['title']));
        $field->save();

        $field = fractal($field, new FieldTransformer())->toArray();

        return response()->json(['field' => $field['data']]);
    }

    public function update(UpdateFieldRequest $request, $fieldId)
    {
        $validated = $request->validated();

        $field = UserField::find($fieldId);
        $field->title = $validated['title'];
        $field->save();

        $field = fractal($field, new FieldTransformer())->toArray();

        return response()->json(['field' => $field['data']]);
    }

    public function remove(DeleteFieldRequest $request, $fieldId)
    {
        UserField::where('id', $fieldId)
            ->delete();

        return response()->json(['status'=>true]);
    }
}
