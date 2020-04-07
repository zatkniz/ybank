<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\Account;
use App\Http\Requests\StoreTransaction;
use App\Events\NewTransaction;

class TransactionController extends Controller
{
    public function getAccountTransactionHistory($id) {
        return Transaction::whereFrom($id)
                            ->orWhere('to' , $id)
                            ->with(['sender:id,name' , 'currency', 'receiver:id,name'])
                            ->get();
    }

    public function save(StoreTransaction $request) {
        try {
            if($request->input('from') == $request->input('to')) abort(400, 'Sender cannot be the same as receiver.');
            
            $sender = Account::find($request->input('from')) ?? abort(404, 'Sender does not exist.');
            $receiver = Account::find($request->input('to')) ?? abort(404, 'Receiver does not exist.');

            if($sender->balance < $request->input('amount')) abort(400, 'The sent amount is higher than available balance.');

            Transaction::firstOrCreate(
                ['id' => $request->input('id')],
                $request->all()
            );

            return event(new NewTransaction($sender , $receiver, $request->input('amount')));

        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
