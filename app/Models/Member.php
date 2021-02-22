<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model {

    protected $table = 'Member';

	public function points() {
		return $this->hasMany('App\Models\Point', 'member_id');
	}
}

/*
	id: int(255) pk
	name: varchar(20)
	phone: varchar(20)
	birthdate: date
	id_card: varchar(10)
	status: enum("1", "2", "3") 
		1: regular 2: alert 3: banned
	created_at: datetime
	updated_at: datetime
*/