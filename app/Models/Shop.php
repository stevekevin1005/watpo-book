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
	location: varchar(20)
	created_at: datetime
	updated_at: datetime
*/