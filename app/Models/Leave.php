<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model {

  protected $table = 'Leave';

  public function ServiceProvider()
  {
    return $this->belongsTo('App\Models\ServiceProvider', 'service_provider_id');
  }
}

/*
	id: int(255) pk
	service_provider_id: service provider table
	start_time: datetime
	end_time: datetime
	created_at: datetime
	updated_at: datetime
*/