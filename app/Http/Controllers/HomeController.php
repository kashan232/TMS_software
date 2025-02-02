<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();

            if ($usertype == 'user') {
                return view('user_panel.dashboard', [
                    'userId' => $userId,
                ]);
            } else if ($usertype == 'admin') {
                // Count Total Customers (Users with 'user' type)
                $totalCustomers = Customer::where('admin_or_user_id', $userId)->count();

                // Count Total Orders
                $totalOrders = Order::count();

                // Count Orders by Status
                $orderReceived = Order::where('admin_or_user_id', $userId)->where('status', 'Order Received')->count();
                $orderUpdated  = Order::where('admin_or_user_id', $userId)->where('status', 'Order Updated')->count();
                $readyOrders   = Order::where('admin_or_user_id', $userId)->where('status', 'Ready')->count();
                $deliveredOrders = Order::where('admin_or_user_id', $userId)->where('status', 'Delivered')->count();

                $startDate = Carbon::now()->subDays(30)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                // Get order count and total amount per day for the last 30 days
                $orders = DB::table('orders')
                    ->select(
                        DB::raw('DATE(created_at) as order_date'),
                        DB::raw('COUNT(*) as order_count'),
                        DB::raw('SUM(net_total) as net_total')
                    )
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('order_date')
                    ->orderBy('order_date', 'ASC')
                    ->get();

                // Convert to JSON format for chart
                $orderLabels = [];
                $orderCounts = [];
                $orderAmounts = [];
                // Prepare data for all 30 days (even those without data)
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i)->toDateString();
                    $orderLabels[] = $date;
                    $orderCounts[] = $orders->where('order_date', $date)->pluck('order_count')->first() ?? 0;
                    $orderAmounts[] = $orders->where('order_date', $date)->pluck('net_total')->first() ?? 0;
                }

                // For the yearly chart, we calculate monthly data for the current year (2025)
                $currentYear = date('Y'); // Get the current year (e.g., 2025)

                $yearlyOrderCounts = [];
                $yearlyOrderAmounts = [];

                // Loop over each month to get data for that month
                for ($month = 1; $month <= 12; $month++) {
                    $startOfMonth = "{$currentYear}-{$month}-01";
                    $endOfMonth = "{$currentYear}-{$month}-31";

                    // Get the total number of orders for the month
                    $totalOrdersMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

                    // Get the total amount for the month
                    $totalAmountMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('net_total');

                    // Store the data in the arrays
                    $yearlyOrderCounts[] = $totalOrdersMonth;
                    $yearlyOrderAmounts[] = $totalAmountMonth;
                }

                return view('admin_panel.dashboard', [
                    'userId'             => $userId,
                    'totalCustomers'     => $totalCustomers,
                    'totalOrders'        => $totalOrders,
                    'orderReceived'      => $orderReceived,
                    'orderUpdated'       => $orderUpdated,
                    'readyOrders'        => $readyOrders,
                    'deliveredOrders'    => $deliveredOrders,
                    'orderLabels'        => $orderLabels,
                    'orderCounts'        => $orderCounts,
                    'orderAmounts'       => $orderAmounts,
                    'yearlyOrderCounts'  => $yearlyOrderCounts,
                    'yearlyOrderAmounts' => $yearlyOrderAmounts,
                ]);
            } else {
                return redirect()->back();
            }
        }
    }
}
