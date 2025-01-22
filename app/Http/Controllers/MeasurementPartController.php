<?php

namespace App\Http\Controllers;

use App\Models\ClothType;
use App\Models\MeasurementPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class MeasurementPartController extends Controller
{
    public function create_measurement_parts()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $MeasurementPart = MeasurementPart::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            return view('admin_panel.measurement_parts.create_measurement_parts', [
                'MeasurementPart' => $MeasurementPart,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function measurment_store(Request $request)
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();

            // Handle image upload if the image is provided
            $imageName = null;  // Default to null if no image is uploaded
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'measurmentParts_images/' . $imageName;

                // Save the original image to the public directory
                $image->move(public_path('measurmentParts_images'), $imageName);
            }

            // Create the product with or without the image
            MeasurementPart::create([
                'admin_or_user_id' => $userId,
                'Measurement_category'  => $request->Measurement_category,
                'Measurement_name'     => $request->Measurement_name,
                'Description'         => $request->Description,
                'image'            => $imageName,  // Store null if no image uploaded
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Product Added Successfully');
        } else {
            return redirect()->back();
        }
    }
}
