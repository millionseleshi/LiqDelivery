<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded=[];

    public function order()
    {
        return $this->belongsTo(CustomerOrder::class);
    }
}
