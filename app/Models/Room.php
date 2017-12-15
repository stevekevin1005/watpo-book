<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {

  protected $table = 'Room';

  public function shop()
  {
    return $this->belongsTo('App\Models\Shop', 'shop_id');
  }

  public function orders()
  {
    return $this->hasMany('App\Models\Order', 'room_id');
  }
}

/*
	id: int(255) pk
	name: varchar(20)
	shower: tinyint(1)
	shop_id: shop table
  person: int(2)
	created_at: datetime
	updated_at: datetime
*/