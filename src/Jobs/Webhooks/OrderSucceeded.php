<?php

namespace Lotuashvili\LaravelBillwerk\Jobs\Webhooks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Lotuashvili\LaravelBillwerk\Billwerk\Order;
use Lotuashvili\LaravelBillwerk\Models\BillwerkCustomer;

/**
 * Class OrderSucceeded
 *
 * Send order confirmation to the user.
 *
 * @package Lotuashvili\LaravelBillwerk\Jobs\Webhooks
 */
class OrderSucceeded implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $contractId;

    /**
     * @var string
     */
    private $orderId;

    /**
     * Create a new job instance.
     *
     * @param string $contractId
     * @param string $orderId
     */
    public function __construct(string $contractId, string $orderId)
    {
        $this->contractId = $contractId;
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $orderClient = new Order();
            $order = $orderClient->get($this->orderId)->data();
            $customer = BillwerkCustomer::byBillwerkId($order->CustomerId)->first();

            if ($customer === null) {
                Log::error('Customer not found: ' . $order->CustomerId);

                return;
            }

            event(new \Lotuashvili\LaravelBillwerk\Events\OrderSucceeded($customer, $order));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
