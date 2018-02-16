<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model {

  protected $table = 'ServiceProvider';

  public function orders()
  {
    return $this->belongsToMany('App\Models\Order', 'service_provider_order', 'service_provider_id','order_id');
  }
  public function leaves()
  {
    return $this->hasMany('App\Models\Leave', 'service_provider_id');
  }
  public function shop()
  {
    return $this->belongsTo('App\Models\Shop', 'shop_id');
  }
  public function shifts()
  {
    return $this->hasMany('App\Models\Shift', 'service_provider_id');
  }
}

/*
	id: int(255) pk
	name: varchar(20)
  service_1: boolean //指壓
  service_2: boolean //油壓
  service_3: boolean //油壓去角質
	shop_id: int(255)
	created_at: datetime
	updated_at: datetime
*/