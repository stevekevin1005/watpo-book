<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlackList extends Model {

  protected $table = 'BlackList';
  protected $guarded = ['id'];
}

/*
	id: int(255) pk
	name: varchar(20)
	phone: varchar(20)
	description: varchar(20)
	overtime: int(5);
	status: tinyint(1)
			close open
	created_at: datetime
	updated_at: datetime
*/