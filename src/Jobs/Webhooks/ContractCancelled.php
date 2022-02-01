<?php

namespace Lotuashvili\LaravelBillwerk\Jobs\Webhooks;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Lotuashvili\LaravelBillwerk\Billwerk\Contract;
use Lotuashvili\LaravelBillwerk\Models\BillwerkContract;
use Lotuashvili\LaravelBillwerk\Models\BillwerkCustomer;

class ContractCancelled implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $contractId;

    /**
     * Create a new job instance.
     *
     * @param string $contractId
     */
    public function __construct(string $contractId)
    {
        $this->contractId = $contractId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contractClient = new Contract();

        try {
            $res = $contractClient->get($this->contractId)->data();
            BillwerkCustomer::where('billwerk_id', $res->CustomerId)->firstOrFail();
            $contract = BillwerkContract::findOrFail($res->Id);

            $contract->update([
                'end_date' => Carbon::parse($contract->EndDate),
            ]);

            event(new \Lotuashvili\LaravelBillwerk\Events\ContractCancelled($contract));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
