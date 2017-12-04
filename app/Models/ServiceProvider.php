<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model {

  protected $table = 'ServiceProvider';
}

/*
	id: int(255) pk
	name: varchar(20)
	shop_id: int(255)
	created_at: datetime
	updated_at: datetime
*/