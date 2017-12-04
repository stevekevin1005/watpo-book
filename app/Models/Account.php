<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {

  protected $table = 'Account';
  protected $primaryKey = 'account';
}

/*
	account: varchar(20) pk
	password: varchar(128) not null
	level: enum("1", "2") 1: admin 2: normal
	created_at: datetime
	updated_at: datetime
*/