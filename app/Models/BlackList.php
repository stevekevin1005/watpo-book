<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlackList extends Model {

  protected $table = 'BlackList';
}

/*
	id: int(255) pk
	name: varchar(20)
	phone: varchar(20)
	created_at: datetime
	updated_at: datetime
*/