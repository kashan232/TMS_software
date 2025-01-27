<?php

namespace App\Http\Controllers;

use App\Models\ClothType;
use App\Models\Customer;
use App\Models\CustomerMeasurement;
use App\Models\MeasurementPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    public function add_Customer()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            // $StaffDesignations = StaffDesignation::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            return view('admin_panel.customer.create_customer', [
                // 'StaffDesignations' => $StaffDesignations,
            ]);
        } else {
            return redirect()->back();
        }
    }


    public function store(Request $request)
    {

        if (Auth::id()) {
            $userId = Auth::id();

            Customer::create([
                'admin_or_user_id' => $userId,
                'customer_number' => $request->customer_number,
                'full_name' => $request->full_name,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'gender' => $request->gender,
                'comment' => $request->comment,
            ]);

            return redirect()->back()->with('success', 'Customer added successfully!');
        } else {
            return redirect()->back();
        }
    }

    public function Customers()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $Customers = Customer::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            return view('admin_panel.customer.customers', [
                'Customers' => $Customers,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function updateCustomer(Request $request)
    {
        if (Auth::id()) {
            try {
                // Find the customer by ID
                $customer = Customer::findOrFail($request->id);

                // Update customer details
                $customer->customer_number = $request->customer_number;
                $customer->full_name = $request->full_name;
                $customer->address = $request->address;
                $customer->phone_number = $request->phone_number;
                $customer->gender = $request->gender;
                $customer->comment = $request->comment;
                $customer->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Customer updated successfully.',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update customer. ' . $e->getMessage(),
                ]);
            }
        } else {
            return redirect()->back();
        }
    }

    public function customer_add_measurement($id)
    {
        if (Auth::id()) {
            $userId = Auth::id();

            // Fetch customer by ID
            $customer = Customer::find($id);

            // Fetch cloth types and their measurement parts
            $clothTypes = ClothType::where('admin_or_user_id', $userId)->get();
            $measurementParts = MeasurementPart::where('admin_or_user_id', $userId)->get();

            // Fetch existing measurements
            $measurements = CustomerMeasurement::where('customer_id', $id)->get();

            return view('admin_panel.customer.customer_add_measurement', [
                'customer' => $customer,
                'clothTypes' => $clothTypes,
                'measurementParts' => $measurementParts,
                'measurements' => $measurements->keyBy(function ($item) {
                    return $item->cloth_type_id . '-' . $item->measurement_part_id;
                }),
            ]);
        } else {
            return redirect()->back();
        }
    }




    public function fetchMeasurementParts(Request $request)
    {
        $clothTypeIds = $request->input('clothTypeIds');
        dd($clothTypeIds);
        // Fetch measurement parts based on selected cloth types
        $measurementParts = MeasurementPart::whereIn('cloth_type_id', $clothTypeIds)->get();

        return response()->json([
            'measurementParts' => $measurementParts
        ]);
    }

    public function customer_measruemt_store(Request $request, $customerId)
    {
        // dd($customerId); // Checking the request data for debugging

        $customer = Customer::findOrFail($customerId);
        // dd($customer);


        // Loop through the measurements and store them
        foreach ($request->measurements as $clothTypeId => $parts) {
            foreach ($parts as $partId => $value) {
                // Ensure we only save non-null values
                if ($value !== null) {
                    CustomerMeasurement::updateOrCreate(
                        [
                            'customer_id' => $customer->id,
                            'cloth_type_id' => $clothTypeId,
                            'measurement_part_id' => $partId,
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Measurements saved successfully.');
    }
}
