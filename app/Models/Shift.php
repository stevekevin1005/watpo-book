<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model {

  protected $table = 'Shift';
  protected $fillable = ['month', 'service_provider_id'];

  public function service_provider()
  {
    return $this->belongsTo('App\Models\ServiceProvider', 'service_provider_id');
  }
}

/*
	id: int(255) pk
	month: char(7)
  start_time: time
  end_time: time
	service_provider_id: int(255)
	created_at: datetime
	updated_at: datetime
*/