<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderManagementController extends Controller
{
    public function add_Order()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            // Fetch customers for the authenticated user
            $customers = Customer::where('admin_or_user_id', $userId)->get();
            // dd($customers);
            // Fetch cloth types and their prices for the authenticated user
            $clothTypes = DB::table('cloth_types')
                ->join('price_lists', 'cloth_types.cloth_type_name', '=', 'price_lists.cloth_type_name')
                ->where('cloth_types.admin_or_user_id', $userId) // Specify table name for admin_or_user_id
                ->select(
                    'cloth_types.cloth_type_name',
                    'cloth_types.cloth_type_gender',
                    'price_lists.Price'
                )
                ->get();
            // dd($clothTypes);
            return view('admin_panel.order.create_order', [
                'customers' => $customers,
                'clothTypes' => $clothTypes,
            ]);
        } else {
            return redirect()->back();
        }
    }
}
