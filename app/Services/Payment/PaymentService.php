<?php

namespace App\Services\Payment;

use Xendit\Configuration;
use Xendit\PaymentRequest\PaymentRequestApi;
use Xendit\Invoice\InvoiceApi;

class PaymentService {
    
    public function bank() {
        Configuration::setXenditKey(env("XENDIT_KEY"));

        $apiInstance = new PaymentRequestApi();
        $idempotency_key = \Str::uuid();

        $payment_request_parameters = new \Xendit\PaymentRequest\PaymentRequestParameters([
            'reference_id' => 'example-ref-1234',
            'currency' => 'IDR',
            'amount' => 85000,
            'country' => 'ID',
            'payment_method' => [
                'type' => 'VIRTUAL_ACCOUNT',
                'reusability' => 'ONE_TIME_USE',
                'reference_id' => 'example-1234',
                'virtual_account' => [
                'channel_code' => 'BCA',
                'channel_properties' => [
                    'customer_name' => 'Ahmad Gunawan',
                    'expires_at' => now()->addDays(1)
                ]
                ]
            ],
            'metadata' => [
                'sku' => 'example-sku-1234'
            ]
        ]);

        $result = $apiInstance->createPaymentRequest($idempotency_key, null, $payment_request_parameters);
        
        return $result['payment_method']['virtual_account'];
    }

}