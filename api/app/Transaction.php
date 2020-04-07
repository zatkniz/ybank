<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'to',
        'from',
        'amount',
        'details',
        'currency_id',
    ];

    public $timestamps = false;

    public function sender() {
        return $this->belongsTo('App\Account', 'from');
    }

    public function receiver() {
        return $this->belongsTo('App\Account', 'to');
    }

    public function currency() {
        return $this->belongsTo('App\Currency');
    }

    public function getAmountAttribute($value) {
        return $this->currency()->first()->sign . $value;
    }
}
