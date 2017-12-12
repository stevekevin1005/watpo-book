<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model {

  protected $table = 'Shop';

  public function serviceProviders()
  {
    return $this->hasMany('App\Models\ServiceProvider', 'shop_id');
  }

  public function rooms()
  {
    return $this->hasMany('App\Models\Room', 'shop_id');
  }
}

/*
	id: int(255) pk
	name: varchar(20)
  description: text
	location: varchar(20)
  start_time: time
  end_time: time
	created_at: datetime
	updated_at: datetime
*/