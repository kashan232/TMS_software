<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffDesignation;
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
                'salary'         => $request->salary,
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
        // Get the cloth type ID from the request
        $edit_staff_id = $request->input('edit_staff_id');
        // dd($edit_staff_id);

        // Update the cloth type in the database
        Staff::where('id', $edit_staff_id)->update([
            'designations'  => $request->designations,
            'full_name'     => $request->full_name,
            'address'         => $request->address,
            'phone_number'         => $request->phone_number,
            'salary'         => $request->salary,
            'updated_at'       => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Staff  updated successfully');
    }
}
