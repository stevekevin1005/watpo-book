<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
class Log extends Model {

  protected $table = 'Log';

  public function Account()
  {
    return $this->belongsTo('App\Models\Account', 'account_id');
  }

  public static function create(array $attributes = [])
	{
	  $log = new Log();
	  foreach ($attributes as $key => $value) {
	  	$log->$key = $value;
	  }
  	$log->account_id = Session::get('account_id');
  	$log->save();
	}
}

/*
	id: int(255) pk
	description: varchar(255)
	account_id: account table
	created_at: datetime
	updated_at: datetime
*/