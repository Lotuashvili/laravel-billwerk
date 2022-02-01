<?php

namespace Lotuashvili\LaravelBillwerk\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lotuashvili\LaravelBillwerk\Models\BillwerkContract;

/**
 * Class UpOrDowngrade
 *
 * @package Lotuashvili\LaravelBillwerk\Events
 */
class UpOrDowngrade
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
