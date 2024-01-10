<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function allTransactions()
    {
        $transactions = Transaction::paginate(10);
        return success('Success', $transactions);
    }

    public function authTransactions()
    {
        $transactions = Transaction::where('user_id', auth()->id())->paginate(10);
        return success('Success', $transactions);
    }

    public function detailTransaction($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction == null) {
            return fail('No result found');
        }

        return success('Success', $transaction);
    }
}
