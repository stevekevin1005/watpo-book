<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;



class Report extends Model
{
    //
    protected $table = 'Report';
    protected $primaryKey = 'order_id';

    public function serviceProviders(){

        return $this->hasMany('App\Models\ServiceProvider', 'service_provider_order', 'order_id','service_provider_id');
    }

    public function belongsToOrder(){

        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}

/*
	order_id: int(255) pk (associate order)
	q0~q7: question
	status: 0: init report 1: phone number miss 2: send report 3: report done 4: report done and response it
    response: text default:null
	created_at: datetime
	updated_at: datetime
*/