<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\SubscriberFieldValue;

class SubscriberFieldTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(SubscriberFieldValue $subscriberField)
    {
        return [
            'id'            => $subscriberField->id,
            'field_id'      => $subscriberField->field->id,
            'name'    => $subscriberField->field->name,
            'title'   => $subscriberField->field->title,
            'type'    => $subscriberField->field->type,
            'value'         => $subscriberField->value
        ];
    }
}
