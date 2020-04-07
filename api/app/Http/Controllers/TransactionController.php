<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class TransactionController extends Controller
{
    public function getAccountTransactionHistory($id) {
        return Transaction::whereFrom($id)
                            ->orWhere('to' , $id)
                            ->with(['sender:id,name' , 'currencyValue', 'receiver:id,name'])
                            ->get();
    }
}
