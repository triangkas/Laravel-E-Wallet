<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Deposit;
use App\Models\TransactionHistory;

class UpdateCustomerWallet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $customerId;
    public $amount;

    /**
     * Create a new job instance.
     */
    public function __construct($customerId, $amount)
    {
        $this->customerId = $customerId;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        
        try {
            $customer = Deposit::where('user_id', $this->customerId)->lockForUpdate()->first();
            if (!$customer) {
                throw new \Exception("Customer not found.");
            } 
            $customer->deposit += $this->amount;
            $customer->save();
            $updateWallet = $customer->save();

            if($updateWallet){
                $paramsHistory = [
                    'amount' => $this->amount,
                    'timestamp' => date('Y-m-d H:i:s'),
                    'status' => '1',
                    'type' => 'C',
                    'description' => '',
                    'user_id' => $this->customerId
                ];
                $this->addHistory($paramsHistory);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            
            $paramsHistory = [
                'amount' => $this->amount,
                'timestamp' => date('Y-m-d H:i:s'),
                'status' => '2',
                'type' => 'C',
                'description' => $e->getMessage(),
                'user_id' => $this->customerId
            ];
            $this->addHistory($paramsHistory);

            Log::error("Failed to update wallet: " . $e->getMessage());
        }
    }

    function addHistory($params){
        $data = new TransactionHistory();
        $data->amount = $params['amount'];
        $data->timestamp = $params['timestamp'];
        $data->status = $params['status'];
        $data->type = $params['type'];
        $data->description = $params['description'];
        $data->user_id = $params['user_id'];
        $resultSave = $data->save();
        return $resultSave;
    }
}
