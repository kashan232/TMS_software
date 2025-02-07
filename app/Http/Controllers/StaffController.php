<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffDesignation;
use App\Models\StaffExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function designations()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $StaffDesignations = StaffDesignation::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            return view('admin_panel.staff_management.staff_designations', [
                'StaffDesignations' => $StaffDesignations,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function store_designations(Request $request)
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();
            StaffDesignation::create([
                'admin_or_user_id'    => $userId,
                'designations'          => $request->designations,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'Staff Designation has been  created successfully');
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        // Get the cloth type ID from the request
        $id_edit_staff_designations = $request->input('id_edit_staff_designations');
        // dd($id_edit_staff_designations);

        // Update the cloth type in the database
        StaffDesignation::where('id', $id_edit_staff_designations)->update([
            'designations' => $request->designations,
        ]);

        return redirect()->back()->with('success', 'Staff Designation updated successfully');
    }


    public function add_staff()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $StaffDesignations = StaffDesignation::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            return view('admin_panel.staff_management.add_staff', [
                'StaffDesignations' => $StaffDesignations,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function store_staff(Request $request)
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();
            // Create the product with or without the image
            Staff::create([
                'admin_or_user_id' => $userId,
                'designations'  => $request->designations,
                'full_name'     => $request->full_name,
                'address'         => $request->address,
                'phone_number'         => $request->phone_number,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Staff Added Successfully');
        } else {
            return redirect()->back();
        }
    }

    public function staffs()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $Staffs = Staff::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            $StaffDesignations = StaffDesignation::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            return view('admin_panel.staff_management.staffs', [
                'Staffs' => $Staffs,
                'StaffDesignations' => $StaffDesignations,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function update_staff(Request $request)
    {

        if (Auth::id()) {
            // Get the cloth type ID from the request
            $edit_staff_id = $request->input('edit_staff_id');
            // dd($edit_staff_id);

            // Update the cloth type in the database
            Staff::where('id', $edit_staff_id)->update([
                'designations'  => $request->designations,
                'full_name'     => $request->full_name,
                'address'         => $request->address,
                'phone_number'         => $request->phone_number,
                'updated_at'       => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Staff  updated successfully');
        } else {
            return redirect()->back();
        }
    }

    public function staff_expenses()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $staffMembers = Staff::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            // $expenses = StaffExpense::with('staff')->orderBy('created_at', 'desc')->get();
            return view('admin_panel.staff_management.staff_expenses', compact('staffMembers'));
        } else {
            return redirect()->back();
        }
    }

    public function store_expense(Request $request)
    {
        if (Auth::id()) {
            $userId = Auth::id();

            // Retrieve the staff member
            $staff = Staff::find($request->staff_id);
            if (!$staff) {
                return back()->withErrors(['staff_id' => 'Staff member not found.']);
            }

            // Calculate total amount for the suits stitched
            $total_amount = $request->suits_stitched * $request->rate_per_suit;

            // Get the previous balance from the staff table
            $previous_balance = $staff->previous_balance;

            // Calculate new balance after payment
            $new_balance = $previous_balance + $total_amount - $request->paid_amount;

            // Update the staff's cumulative paid amount
            $total_paid = $staff->total_paid + $request->paid_amount;

            // Update the staff's cumulative total amount
            $total_amount_cumulative = $staff->total_amount + $total_amount;

            // Determine the payment status
            $status = ($new_balance <= 0) ? 'Paid' : 'Unpaid';

            // Calculate the closing balance (similar logic as frontend)
            $closing_balance = $previous_balance + $total_amount - $request->paid_amount;

            // Update the staff's balances, total amount, and status
            $staff->update([
                'previous_balance' => $new_balance,
                'total_paid' => $total_paid,
                'total_amount' => $total_amount_cumulative,
                'status' => $status,
            ]);

            // Store the expense in StaffExpense table
            StaffExpense::create([
                'admin_or_user_id' => $userId,
                'staff_id' => $staff->id,
                'week_start_date' => $request->week_start_date,
                'week_end_date' => $request->week_end_date,
                'previous_balance' => $previous_balance,
                'suits_stitched' => $request->suits_stitched,
                'rate_per_suit' => $request->rate_per_suit,
                'total_amount' => $total_amount,
                'paid_amount' => $request->paid_amount,
                'closing_balance' => $closing_balance, // Save closing balance
            ]);

            return redirect()->back()->with('success', 'Expense saved successfully.');
        } else {
            return redirect()->back();
        }
    }


    public function getPreviousBalance($staff_id)
    {
        $staff = Staff::find($staff_id);

        // Return the previous balance, or 0 if the staff member doesn't exist
        return response()->json([
            'previous_balance' => $staff ? $staff->previous_balance : 0
        ]);
    }

    public function staff_expenses_record()
    {
        if (Auth::id()) {
            $userId = Auth::id();
    
            // Fetch all staff expenses with related staff details
            $StaffExpenses = StaffExpense::where('admin_or_user_id', $userId)
                ->with('staff') // Eager load related staff
                ->get();
    
            return view('admin_panel.staff_management.staff_expenses_record', compact('StaffExpenses'));
        } else {
            return redirect()->back();
        }
    }


}
