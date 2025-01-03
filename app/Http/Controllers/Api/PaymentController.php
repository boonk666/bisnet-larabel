<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\Payment\GetPerCustomerForCurrentMonthRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Midtrans\Snap;

class PaymentController extends Controller
{
    
    /**
     * perCustomerForCurrentMonth
     *
     * @return void
     */
    public function perCustomerForCurrentMonth(GetPerCustomerForCurrentMonthRequest $request) {
        $month = $request->input('month');

        $customerQuery = Customer::when($request->has('is_paid'), function ($query) use ($request, $month) {
            $isPaid = $request->get('is_paid');
            
            if ($isPaid) {
                $query->isPaidForCurrentMonth($month);
            } else {
                $query->isNotPaidForCurrentMonth($month);
            }
        })->with('payments', function ($query) use ($month) {
            $startAt = now()->setMonth($month)->startOfMonth();
            $endAt = now()->setMonth($month)->endOfMonth();

            $query
                ->where('status', 'success')
                ->whereBetween('created_at', [$startAt, $endAt]);
        });

        return Datatables::of($customerQuery)->toJson();
    }

    public function submit(Request $request) {
        $user = $request->user();
        $customer = Customer::firstWhere('id_pengguna', $user->id_pengguna);

        $payment = $customer->payments()->first();

        if ($payment) {
            return $payment;
        }

        return DB::transaction(function () use ($user, $customer) {
            $code = Str::random(8);

            $snap = Snap::createTransaction([
                'transaction_details' => [
                    'order_id' => $code,
                    'gross_amount' => 85000
                ],
                'customer_details' => [
                    'first_name' => $customer->nama,
                    'phone' => $customer->no_telp
                ],
                'item_details' => [
                    [
                        'id' => 'paket1',
                        'price' => 85000,
                        'quantity' => 1,
                        'name' => 'Paket 1'
                    ]
                ]
            ]);

            $payment = Payment::create([
                'kode_pembayaran' => $code,
                'id_pelanggan' => $customer->id_pelanggan,
                'snap_url' => $snap->redirect_url
            ]);
    
            return $payment;
        });
    }

    public function getMonthly(Request $request) {
        $user = $request->user();
        $customer = Customer::firstWhere('id_pengguna', $user->id_pengguna);

        return $customer->payments()
            ->where('status', 'success')
            ->whereBetween('created_at', [now()->startOf('year'), now()->endOf('year')])
            ->get();
    }

}
