<?php

namespace App\Http\Controllers;

use App\Models\Order;

class EstimationController extends Controller
{
    public function index()
    {
        return view('estimasi.index', ['orders' => Order::where('status', 'diproses')->orderBy('estimated_finish_date')->paginate(20)]);
    }
}
