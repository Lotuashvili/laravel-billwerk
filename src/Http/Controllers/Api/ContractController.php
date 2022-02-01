<?php

namespace Lotuashvili\LaravelBillwerk\Http\Controllers\Api;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Lotuashvili\LaravelBillwerk\Billwerk\Contract;
use Lotuashvili\LaravelBillwerk\Http\Controllers\Controller;

/**
 * Class ContractController
 *
 * @package Lotuashvili\LaravelBillwerk\Http\Controllers\Api
 */
class ContractController extends Controller
{
    /**
     * @param $contractId
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getSelfServiceToken($contractId)
    {
        $cacheKey = 'billwerk_contract_' . $contractId . '_token';

        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        $contractService = new Contract();

        try {
            $tokenData = $contractService->selfServiceToken($contractId)->data();
            $expiry = Carbon::parse($tokenData->Expiry);
            $tokenExpireIn = $expiry->diffInMinutes(Carbon::now()) - 60;
            Cache::put($cacheKey, $tokenData, $tokenExpireIn);
            $res = $tokenData;
        } catch (Exception $e) {
            throw new Exception('Error while fetching token from API');
        }

        return response()->json($res ?? []);
    }
}
