<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
	const STATE_ACTIVE = 'active';
	const STATE_UNSUBSCRIBED = 'unsubscribed';
	const STATE_JUNK = 'junk';
	const STATE_BOUNCED = 'bounced';
	const STATE_UNCONFIRMED = 'unconfirmed';

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function field_values()
    {
    	return $this->hasMany('App\SubscriberFieldValue', 'subscriber_id', 'id');
    }
}
