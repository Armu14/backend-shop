<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->when(request()->q, function ($customers) {
            $customers = $customers->where('invoice','like','%'. request()->q. '%');
        })->paginate(10);
dd($customers);
        return view('admin.customer.index', compact('customers'));
    }
}
