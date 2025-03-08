<?php

namespace App\Http\Controllers;

use App\Mail\CustomerMail;
use App\Models\ClothType;
use App\Models\ClothVariant;
use App\Models\Customer;
use App\Models\CustomerMeasurement;
use App\Models\CustomerVariant;
use App\Models\MeasurementPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

            // Default image name null rakhein
            $imageName = null;

            // Image upload logic
            if ($request->hasFile('customer_image')) {
                $image = $request->file('customer_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'customer_images/' . $imageName;

                // Image ko public folder me move karna
                $image->move(public_path('customer_images'), $imageName);
            }

            // Customer record create karna
            Customer::create([
                'admin_or_user_id' => $userId,
                'customer_number' => $request->customer_number,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'gender' => $request->gender,
                'image' => $imageName, // Agar image nahi select ki gayi to null save hoga
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

            $Customers = Customer::where('admin_or_user_id', $userId)->paginate(5000); // Adjust according to your database structure

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
                $customer->email = $request->email;
                $customer->address = $request->address;
                $customer->phone_number = $request->phone_number;
                $customer->city = $request->city;
                $customer->gender = $request->gender;
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

            // Fetch customer
            $customer = Customer::find($id);

            // Fetch cloth types and measurement parts
            $clothTypes = ClothType::where('admin_or_user_id', $userId)->get();
            $measurementParts = MeasurementPart::where('admin_or_user_id', $userId)->get();

            // Fetch cloth variants
            $clothVariants = ClothVariant::where('admin_or_user_id', $userId)->get();

            // Fetch existing measurements
            $measurements = CustomerMeasurement::where('customer_id', $id)->get()->keyBy(function ($item) {
                return $item->cloth_type_id . '-' . $item->measurement_part_id;
            });
            $customerDescription = CustomerMeasurement::where('customer_id', $id)->value('description');

            // Fetch existing customer variants (YES/NO selections)
            $existingVariants = CustomerVariant::where('customer_id', $id)
                ->pluck('value', 'variants_part_id'); // Gives [variant_id => 'yes/no']

            return view('admin_panel.customer.customer_add_measurement', [
                'customer' => $customer,
                'clothTypes' => $clothTypes,
                'measurementParts' => $measurementParts,
                'measurements' => $measurements,
                'clothVariants' => $clothVariants,
                'existingVariants' => $existingVariants,  // Pass existing variants
                'customerDescription' => $customerDescription, // Add this line
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
        $customer = Customer::findOrFail($customerId);
        // Save Measurements
        if ($request->has('measurements')) {
            foreach ($request->measurements as $clothTypeId => $parts) {
                foreach ($parts as $partId => $value) {
                    if (!empty($value)) {
                        CustomerMeasurement::updateOrCreate(
                            [
                                'customer_id' => $customer->id,
                                'cloth_type_id' => $clothTypeId,
                                'measurement_part_id' => $partId,
                            ],
                            [
                                'value' => $value,
                                'description' => $request->description, // Correct way to save description
                            ]
                        );
                    }
                }
            }
        }


        // Save Cloth Variants
        if ($request->has('cloth_variant')) {
            foreach ($request->cloth_variant as $variantId => $value) {
                $clothVariant = ClothVariant::find($variantId);

                if ($clothVariant) {
                    $clothTypeId = $clothVariant->cloth_type_id ?? array_key_first($request->measurements); // Fallback to first cloth type

                    CustomerVariant::updateOrCreate(
                        [
                            'customer_id' => $customer->id,
                            'variants_part_id' => $variantId,
                        ],
                        [
                            'cloth_type_id' => $clothTypeId, // Assign correct cloth type
                            'value' => $value,
                        ]
                    );
                }
            }
        }


        return redirect()->back()->with('success', 'Measurements and Variants saved successfully.');
    }



    public function showEmailForm($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin_panel.customer.send_email', compact('customer'));
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        $data = [
            'name' => $customer->full_name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message
        ];

        Mail::to($request->email)->send(new CustomerMail($data));

        return redirect()->back()->with('success', 'Email Sent Successfully!');
    }
}
