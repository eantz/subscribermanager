<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\UserField;

class FieldTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(UserField $field)
    {
        return [
            'id'        => $field->id,
            'user_id'   => $field->user_id,
            'title'     => $field->title,
            'type'      => ucwords($field->type),
            'name'      => $field->placeholder
        ];
    }
}
