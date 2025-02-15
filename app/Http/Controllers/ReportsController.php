<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Staff;
use App\Models\StaffExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{

    public function reports_staff_expense()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $staffMembers = Staff::where('admin_or_user_id', $userId)->get();
            return view('admin_panel.reports.staff_expense_reports', compact('staffMembers'));
        } else {
            return redirect()->back();
        }
    }

    public function fetch_staff_expenses(Request $request)
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $staffMembers = Staff::where('admin_or_user_id', $userId)->get();

            // AJAX request handle karna
            if ($request->ajax()) {
                $query = StaffExpense::with('staff')->whereHas('staff', function ($q) use ($userId) {
                    $q->where('admin_or_user_id', $userId);
                });

                // Staff Member filter (Agar select kiya ho)
                if (!empty($request->staff_member)) {
                    $query->where('staff_id', $request->staff_member);
                }

                // Date Range filter (Agar select kiya ho)
                if (!empty($request->week_start_date) && !empty($request->week_end_date)) {
                    $query->whereBetween('week_start_date', [$request->week_start_date, $request->week_end_date]);
                }

                // Filtered data fetch karna
                $staffExpenses = $query->get();
                return response()->json($staffExpenses);
            }

            return view('admin_panel.reports.staff_expense_reports', compact('staffMembers'));
        } else {
            return redirect()->back();
        }
    }

    public function reports_customer_wise()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $Customers = Customer::where('admin_or_user_id', $userId)->get();
            return view('admin_panel.reports.reports_customer_wise', compact('Customers'));
        } else {
            return redirect()->back();
        }
    }

    public function fetchCustomerReport(Request $request)
    {
        // Fetch orders for the selected customer within the given date range
        $orders = Order::where('customer_number', $request->customer_number)
            ->whereBetween('order_receiving_date', [$request->week_start_date, $request->week_end_date])
            ->get();

        // Total Orders
        $totalOrders = $orders->count();

        // Completed Orders (Delivered status)
        $completedOrders = $orders->where('status', 'Delivered')->count();

        // Ready Orders
        $readyOrders = $orders->where('status', 'Ready')->count();

        // Assigned Orders
        $assignedOrders = $orders->whereNotNull('assign_name')->count();

        // Total Price (sum of net_total)
        $totalPrice = $orders->sum('net_total');

        // Total Paid Amount
        $totalPaid = $orders->sum('advance_paid');

        // Remaining Balance
        $remainingBalance = $orders->sum('remaining');

        return response()->json([
            'orders' => $orders,
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'ready_orders' => $readyOrders,
            'assigned_orders' => $assignedOrders,
            'total_price' => $totalPrice,
            'total_paid' => $totalPaid,
            'remaining_balance' => $remainingBalance
        ]);
    }

    public function get_customer_details(Request $request)
    {
        $customer = Customer::where('customer_number', $request->customer_number)->first();
        return response()->json($customer);
    }
}
