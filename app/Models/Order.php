<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

  protected $table = 'Order';

  public function room()
  {
    return $this->belongsTo('App\Models\Room', 'room_id');
  }

  public function service()
  {
    return $this->belongsTo('App\Models\Service', 'service_id');
  }
  public function serviceProvider()
  {
    return $this->belongsToMany('App\Models\ServiceProvider', 'service_provider_order', 'order_id','service_provider_id');
  }
}

/*
	id: int(255) pk
	name: varchar(20)
	phone: varchar(20)
  person: int(2)
	status: enum("1", "2", "3", "4", "5") 
					1: customer book 2: staff book 3: customer cancel 4: staff cancel 5: order success
	service_id: service table
	room_id: room table
	start_time: datetime
	end_time: datetime
	created_at: datetime
	updated_at: datetime
*/