<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model {

    protected $table = 'Point';

	public function order() {
		return $this->belongsTo('App\Models\Order', 'order_id');
	}

	public function member() {
		return $this->belongsTo('App\Models\Member', 'member_id');
	}
}

/*
	id: int(255) pk
	point: int(4)
	order_id: int(255)
	member_id: int(255)
	reason: text
	created_at: datetime
	updated_at: datetime
*/