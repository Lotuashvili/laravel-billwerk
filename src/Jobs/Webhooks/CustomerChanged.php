<?php

namespace Lotuashvili\LaravelBillwerk\Jobs\Webhooks;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Lotuashvili\LaravelBillwerk\Billwerk\Customer;
use Lotuashvili\LaravelBillwerk\Models\BillwerkCustomer;

class CustomerChanged implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $customerId;

    /**
     * Create a new job instance.
     *
     * @param string $customerId
     */
    public function __construct(string $customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $customerClient = new Customer();
        $customer = $customerClient->get($this->customerId)->data();

        BillwerkCustomer::where('billwerk_id', $this->customerId)->update([
            'customer_name' => $customer->CustomerName,
            'customer_sub_name' => $customer->CustomerSubName,
            'company_name' => $customer->CompanyName ?? '',
            'first_name' => $customer->FirstName ?? '',
            'last_name' => $customer->LastName ?? '',
            'language' => $customer->Language ?? null,
            'vat_id' => $customer->VatId ?? '',
            'email_address' => $customer->EmailAddress,
            'notes' => $customer->Notes ?? '',
            'street' => $customer->Address->Street ?? '',
            'house_number' => $customer->Address->HouseNumber ?? '',
            'postal_code' => $customer->Address->PostalCode ?? '',
            'city' => $customer->Address->City ?? '',
            'country' => $customer->Address->Country ?? '',
        ]);
    }
}
