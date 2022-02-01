<?php

namespace Lotuashvili\LaravelBillwerk\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lotuashvili\LaravelBillwerk\Models\BillwerkCustomer;

/**
 * Class OrderSucceeded
 *
 * @package Lotuashvili\LaravelBillwerk\Events
 */
class OrderSucceeded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \Lotuashvili\LaravelBillwerk\Models\BillwerkCustomer
     */
    public $customer;

    public $order;

    /**
     * Create a new event instance.
     *
     * @param BillwerkCustomer $customer
     * @param $order
     */
    public function __construct(BillwerkCustomer $customer, $order)
    {
        $this->customer = $customer;
        $this->order = $order;
    }
}
