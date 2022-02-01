<?php

namespace Lefamed\LaravelBillwerk\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lefamed\LaravelBillwerk\Models\BillwerkContract;

/**
 * Class ContractCancelled
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class ContractCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contract;

    /**
     * Create a new event instance.
     *
     * @param BillwerkContract $contract
     */
    public function __construct(BillwerkContract $contract)
    {
        $this->contract = $contract;
    }
}
