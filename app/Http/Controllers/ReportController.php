<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class ReportController extends Controller
{
    
    public function index() {
        $total_payment = Payment::where('status', 'success')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        return view('report.index', [
            'income' => $total_payment * 85000
        ]);
    }

}
