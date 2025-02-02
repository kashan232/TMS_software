<?php

namespace App\Http\Controllers;

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
}
