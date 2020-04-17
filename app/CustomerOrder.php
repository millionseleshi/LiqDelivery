<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    protected $guarded = [];

    protected $table = "customer_orders";

    protected $casts = [
        'ordered_date' => 'd-m-Y H:i:s.u',
    ];
    protected $appends = [
        'when_is_pick_up'
    ];

    public function getWhenIsPickUpAttribute()
    {
        return Carbon::parse(Carbon::now())->diffForHumans($this->attributes['ordered_date']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
