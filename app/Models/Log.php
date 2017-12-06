<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {

  protected $table = 'Log';

  public function Account()
  {
    return $this->belongsTo('App\Models\Account', 'account_id');
  }
}

/*
	id: int(255) pk
	description: varchar(255)
	account_id: account table
	created_at: datetime
	updated_at: datetime
*/