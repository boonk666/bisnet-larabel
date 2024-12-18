<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;
        $payment = Payment::where('kode_pembayaran', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
                if ($request->payment_type == 'credit_card') {
                    if ($request->fraud_status == 'challenge') {
                        $payment->update(['status' => 'pending']);
                    } else {
                        $payment->update(['status' => 'success']);
                    }
                }
                break;
            case 'settlement':
                $payment->update(['status' => 'success']);
                break;
            case 'pending':
                $payment->update(['status' => 'pending']);
                break;
            case 'deny':
                $payment->update(['status' => 'failed']);
                break;
            case 'expire':
                $payment->update(['status' => 'expired']);
                break;
            case 'cancel':
                $payment->update(['status' => 'canceled']);
                break;
            default:
                $payment->update(['status' => 'unknown']);
                break;
        }

        return response()->json(['message' => 'Callback received successfully']);
    }
}