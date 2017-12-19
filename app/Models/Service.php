<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model {

  protected $table = 'Service';

  public function orders()
  {
    return $this->hasMany('App\Models\Order', 'service_id');
  }
}

/*
	id: int(255) pk
	title: varchar(20)
	price: int(5)
	time: int(5)
	shower: int(1) 0: no 1:optional 2:need
	created_at: datetime
	updated_at: datetime
*/