<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\UpdateCustomerWallet;

class UpdateWalletController extends Controller
{
    function show() {
        return view('updateWallet');
    }

    public function updateWallet(Request $request){
        $customerId = $request->customerId;
        $amount = $request->amount;
        
        UpdateCustomerWallet::dispatch($customerId, $amount);

        return response()->json(['status' => 'success', 'message' => 'Success Top Up Deposit (Update with queued)']);
    }
}
