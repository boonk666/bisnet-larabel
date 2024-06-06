<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\Payment\GetPerCustomerForCurrentMonthRequest;
use App\Http\Requests\Api\Payment\CreatePaymentRequest;
use DataTables;
use App\Services\Payment\PaymentService;

class PaymentController extends Controller
{
    
    /**
     * perCustomerForCurrentMonth
     *
     * @return void
     */
    public function perCustomerForCurrentMonth(GetPerCustomerForCurrentMonthRequest $request) {
        $customerQuery = Customer::when($request->has('is_paid'), function ($query) use ($request) {
            $isPaid = $request->get('is_paid');
            
            if ($isPaid) {
                $query->isPaidForCurrentMonth();
            } else {
                $query->isNotPaidForCurrentMonth();
            }
        });

        return Datatables::of($customerQuery)->toJson();
    }
    
    /**
     * bank
     *
     * @return void
     */
    public function bank(PaymentService $paymentService) {
        $va = $paymentService->bank();

        return [
            'amount' => $va['amount'],
            'account_number' => $va['channel_properties']['virtual_account_number']
        ];
    }

}
