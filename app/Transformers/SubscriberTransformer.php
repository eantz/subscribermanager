<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Subscriber;

class SubscriberTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Subscriber $subscriber)
    {
        return [
            'id'        => $subscriber->id,
            'email'     => $subscriber->email,
            'name'      => $subscriber->name,
            'state'     => $subscriber->state
        ];
    }
}
