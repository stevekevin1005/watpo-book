<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {

    protected $table = 'Account';

    public function logs()
    {
        return $this->hasMany('App\Models\Log', 'account_id');
    }

    public function service_provider()
    {
        return $this->belongsTo('App\Models\ServiceProvider', 'service_provider_id');
    }
}

/*
  id: int(255) pk
	account: varchar(20)
	password: varchar(128) not null
	level: enum("1", "2") 1: admin 2: counter 3:worker
    service_provider_id: int(255)
	created_at: datetime
	updated_at: datetime
*/