<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserField extends Model
{
	const TYPE_DATE = 'date';
	const TYPE_NUMBER = 'number';
	const TYPE_STRING = 'string';
	const TYPE_BOOLEAN = 'boolean';

    public function user()
    {
    	return	$this->belongsTo('App\User', 'user_id', 'id');
    }
}
