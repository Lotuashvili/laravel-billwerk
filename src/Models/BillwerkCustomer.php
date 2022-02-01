<?php

namespace Lotuashvili\LaravelBillwerk\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Lotuashvili\LaravelBillwerk\Billwerk\Customer;
use Lotuashvili\LaravelBillwerk\Jobs\SyncBillwerkCustomer;
use Lotuashvili\LaravelBillwerk\Transformers\Model\CustomerTransformer;

/**
 * Class Customer
 *
 * @package Lotuashvili\LaravelBillwerk\Models
 */
class BillwerkCustomer extends Model
{
    use Notifiable;

    protected $fillable = [
        'billable_id',
        'customer_name',
        'customer_sub_name',
        'company_name',
        'first_name',
        'last_name',
        'language',
        'vat_id',
        'email_address',
        'nodes',

        'street',
        'house_number',
        'postal_code',
        'city',
        'country',
    ];

    public function scopeByBillwerkId(Builder $builder, $id)
    {
        return $builder->where('billwerk_id', $id);
    }

    /**
     * @return string
     */
    public function routeNotificationForMail(): string
    {
        return $this->email_address;
    }

    /**
     * @return array
     */
    public function toBillwerkArray(): array
    {
        return [
            'CompanyName' => $this->company_name,
            'EmailAddress' => $this->email_address,
        ];
    }

    /**
     * @return HasMany
     */
    public function contracts()
    {
        return $this->hasMany(BillwerkContract::class, 'customer_id');
    }

    protected static function boot()
    {
        parent::boot();

        // On Create Event
        static::creating(function ($values) {
            if (config('billwerk.sync') === false) {
                $values['billwerk_id'] = Str::random(24);

                return;
            }

            $customerClient = new Customer();
            $res = $customerClient->post((new CustomerTransformer())->transform($values))->data();
            $values['billwerk_id'] = $res->Id;
        });

        // On Update Event
        static::updated(function (BillwerkCustomer $customer) {
            if (config('billwerk.sync') === false) {
                return;
            }

            dispatch(new SyncBillwerkCustomer($customer));
        });
    }
}
