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
	description_tw: text
	description_en: text
	description_jp: text
	price: int(5)
	time: int(5)
	created_at: datetime
	updated_at: datetime
*/