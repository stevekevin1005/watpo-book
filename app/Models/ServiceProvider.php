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
    public function account()
    {
        return $this->hasOne('App\Models\Account', 'service_provider_id');
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                    ->with([$relation => $constraint]);
    }

    public function scopeFreeTime($query, $month, $start_time, $end_time){
        return $query->whereHas('shifts' ,function ($query) use ($month) {
                    $query->where('month', $month);
                })->with(['shifts' => function ($query) use ($month) {
                    $query->where('month', $month);
                }])->whereDoesntHave('leaves' ,function ($query) use ($start_time, $end_time) {
                    $query->where('start_time', '<', $end_time);
                    $query->where('end_time', '>', $start_time);
                })->whereDoesntHave('orders' ,function ($query) use ($start_time, $end_time) {
                    $query->whereNotIn('status', [3,4,6]);
                    $query->where('start_time', '<', $end_time);
                    $query->where('end_time', '>', $start_time);
                });
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