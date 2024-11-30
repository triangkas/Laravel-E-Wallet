<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionHistory;
use App\Models\Deposit;
use Auth;

class TransactionHistoryController extends Controller
{
    public function __construct()
    {
        $this->ObjDeposit = new Deposit();
        $this->ObjTransactionHistory = new TransactionHistory();
    }

    function show() {
        $listData = $this->ObjTransactionHistory->orderBy('timestamp', 'desc')->paginate(10);
        $deposit = $this->ObjDeposit->where('user_id', Auth::user()->id)->first();
        $data = array(
            'list_data' => $listData,
            'deposit' => number_format($deposit->deposit, 2, ',','.')
        );

        return view('transactionHistory', $data);
    }
}
