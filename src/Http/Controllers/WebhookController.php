<?php

namespace Lotuashvili\LaravelBillwerk\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lotuashvili\LaravelBillwerk\Events\RecurringBillingApproaching;
use Lotuashvili\LaravelBillwerk\Jobs\Webhooks\ContractCancelled;
use Lotuashvili\LaravelBillwerk\Jobs\Webhooks\ContractChanged;
use Lotuashvili\LaravelBillwerk\Jobs\Webhooks\ContractCreated;
use Lotuashvili\LaravelBillwerk\Jobs\Webhooks\CustomerChanged;
use Lotuashvili\LaravelBillwerk\Jobs\Webhooks\OrderSucceeded;

/**
 * Class WebhookController
 */
class WebhookController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request): Response
    {
        if (!$request->isJson()) {
            abort(Response::HTTP_NOT_ACCEPTABLE, 'Please provide JSON body!');
        }

        $content = json_decode($request->getContent());
        switch ($content->Event) {
            case 'CustomerChanged':
                dispatch(new CustomerChanged($content->CustomerId));
                break;
            case 'ContractCreated':
                dispatch(new ContractCreated($content->ContractId));
                break;
            case 'ContractChanged':
                dispatch(new ContractChanged($content->ContractId));
                break;
            case 'ContractCancelled':
                dispatch(new ContractCancelled($content->ContractId));
                break;
            case 'OrderSucceeded':
                dispatch(new OrderSucceeded($content->ContractId, $content->OrderId));
                break;
            case 'RecurringBillingApproaching':
                event(new RecurringBillingApproaching($content->ContractId));
                break;
        }

        return response('', Response::HTTP_ACCEPTED);
    }
}
