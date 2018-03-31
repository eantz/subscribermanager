<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriberFieldValue extends Model
{
    public function subscriber()
    {
        return $this->belongsTo('App\Subscriber', 'subscriber_id', 'id');
    }

    public function field()
    {
        return $this->belongsTo('App\UserField', 'user_field_id', 'id');
    }
}
