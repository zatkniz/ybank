<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function sender() {
        return $this->belongsTo('App\Account', 'from');
    }

    public function receiver() {
        return $this->belongsTo('App\Account', 'to');
    }

    public function currencyValue() {
        return $this->belongsTo('App\Currency', 'currency');
    }

    public function getAmountAttribute($value) {
        return $this->currencyValue()->first()->sign . $value;
    }
}
