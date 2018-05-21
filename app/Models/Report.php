<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;



class Report extends Model
{
    //
    protected $table = 'Report';

    public function serviceProviders(){

        return $this->belongsToMany('App\Models\ServiceProvider', 'service_provider_order', 'order_id','service_provider_id');
    }

    public function belongsToOrder(){

        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}
