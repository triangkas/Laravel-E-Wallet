<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Deposit;
use App\Models\TransactionHistory;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->ObjUser = new User();
        $this->ObjDeposit = new Deposit();
        $this->ObjTransactionHistory = new TransactionHistory();
    }

    function useDeposit(Request $request){
        #1. Validation
        $rules = [
            'order_id' => 'required',
            'amount' => 'required|numeric',
            'timestamp' => 'required|date_format:Y-m-d H:i:s'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response(['status' => false, 'message' => $validator->errors()->all(), 'data' => null]);
        }

        #2. Get User
        $authorizationHeader = trim($request->header('Authorization'));
        $token = trim(substr($authorizationHeader, 7));
        $user = $this->ObjUser->where('access_token', $token)->first();
        if(!$user || empty($user)){
            return response(['status' => false, 'message' => 'User not found', 'data' => null]);
        }

        #3. Cek Deposit
        $userDeposit = $this->ObjDeposit->where('user_id', $user->id)->first();
        if(!$userDeposit || empty($userDeposit) || number_format($userDeposit->deposit, 2, '.', '') < number_format($request->amount, 2, '.', '') ){
            $messageDescription = 'Insufficient deposit';
            $paramsHistory = [
                'order_id' => $request->order_id,
                'amount' => $request->amount,
                'timestamp' => $request->timestamp,
                'status' => '2',
                'type' => 'D',
                'description' => $messageDescription,
                'user_id' => $user->id
            ];
            $this->addHistory($paramsHistory);
            return response([
                'status' => false, 
                'message' => $messageDescription, 
                'data' => [
                    'order_id' => $request->order_id,
                    'amount' => number_format($request->amount, 2, '.', ''),
                    'status' => 'Failed'
                ]
            ]);
        }

        #4. Transaction 
        $paramUpdateDeposit = [
            'new_deposit' => ($userDeposit->deposit - $request->amount),
            'user_id' => $user->id
        ];
        $updateDeposit = $this->updateDeposit($paramUpdateDeposit);
        if($updateDeposit){
            $status = true;
            $message = 'Success';
            $messageDescription = '';
            $dataStatus = '1';
            $dataStatusInfo = 'Success';
        } else {
            $status = false;
            $message = 'Failed';
            $messageDescription = 'Failed update deposit';
            $dataStatus = '2';
            $dataStatusInfo = 'Failed';
        }

        $paramsHistory = [
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'timestamp' => $request->timestamp,
            'status' => $dataStatus,
            'type' => 'D',
            'description' => $messageDescription,
            'user_id' => $user->id
        ];
        $this->addHistory($paramsHistory);
        return response([
            'status' => $status, 
            'message' => $message, 
            'data' => [
                'order_id' => $request->order_id,
                'amount' => number_format($request->amount, 2, '.', ''),
                'status' => $dataStatusInfo
            ]
        ]);
    }

    function addHistory($params){
        $data = $this->ObjTransactionHistory;
        $data->order_id = $params['order_id'];
        $data->amount = $params['amount'];
        $data->timestamp = $params['timestamp'];
        $data->status = $params['status'];
        $data->type = $params['type'];
        $data->description = $params['description'];
        $data->user_id = $params['user_id'];
        $resultSave = $data->save();
        return $resultSave;
    }

    function updateDeposit($params) {
        $data = $this->ObjDeposit->where('user_id', $params['user_id'])->first();
        $data->deposit = $params['new_deposit'];
        $resultSave = $data->save();
        return $resultSave;
    }
}
