<?php

namespace Lefamed\LaravelBillwerk\Jobs\Webhooks;

use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Lefamed\LaravelBillwerk\Billwerk\Contract;
use Lefamed\LaravelBillwerk\Events\UpOrDowngrade;
use Lefamed\LaravelBillwerk\Models\BillwerkContract;
use Lefamed\LaravelBillwerk\Models\BillwerkCustomer;

class ContractChanged implements ShouldQueue
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
            /** @var BillwerkContract $contract */
            $contract = BillwerkContract::findOrFail($res->Id);
            BillwerkCustomer::where('billwerk_id', $res->CustomerId)->firstOrFail();

            if (isset($res->EndDate) && Carbon::parse($res->EndDate)->isPast()) {
                // contract has ended, remove it
                $contract->delete();

                return;
            }

            $contract->plan_id = $res->PlanId;
            $contract->plan_variant_id = $res->PlanVariantId;
            $changed = $contract->isDirty();
            $contract->save();

            if ($changed) {
                event(new UpOrDowngrade($contract));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
