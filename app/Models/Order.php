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

  public function shop()
  {
    return $this->belongsTo('App\Models\Shop', 'shop_id');
  }

  public function account()
  {
    return $this->belongsTo('App\Models\Account', 'account_id');
  }

  public function serviceProviders()
  {
    return $this->belongsToMany('App\Models\ServiceProvider', 'service_provider_order', 'order_id','service_provider_id');
  }
}

/*
	id: int(255) pk
	name: varchar(20)
	phone: varchar(20)
    person: int(2)
	status: enum("1", "2", "3", "4", "5", "6") 
					1: customer book 2: staff book 3: customer cancel 4: staff cancel 5: order success 6: system cancel
	service_id: service table
	room_id: room table
    shop_id: shop table
	start_time: datetime
	end_time: datetime
    account_id: int(255) if the order is booked by worker, it must save have worker id.
	created_at: datetime
	updated_at: datetime
*/