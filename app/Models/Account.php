<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {

  protected $table = 'Account';

  public function logs()
  {
    return $this->hasMany('App\Models\Log', 'account_id');
  }

}

/*
  id: int(255) pk
	account: varchar(20)
	password: varchar(128) not null
	level: enum("1", "2") 1: admin 2: normal
	created_at: datetime
	updated_at: datetime
*/