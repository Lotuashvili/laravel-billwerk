<?php

namespace Lefamed\LaravelBillwerk\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class RecurringBillingApproaching
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class RecurringBillingApproaching
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contract;

    /**
     * Create a new event instance.
     *
     * @param $contract
     */
    public function __construct($contract)
    {
        $this->contract = $contract;
    }
}
