<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $with = [
        'currency'
    ];

    public function currency() {
        return $this->belongsTo('App\Currency');
    }
}
