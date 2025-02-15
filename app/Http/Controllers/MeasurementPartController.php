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

            $ClothTypes = ClothType::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            return view('admin_panel.measurement_parts.create_measurement_parts', [
                'ClothTypes' => $ClothTypes,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function measurment_store(Request $request)
    {
        $request->validate([
            'measurement_category' => 'required|string',
            'measurement_names' => 'required|array',
            'measurement_names.*' => 'string|min:1',
        ]);

        $userId = Auth::id();

        foreach ($request->measurement_names as $name) {
            MeasurementPart::create([
                'admin_or_user_id' => $userId,
                'Measurement_category' => $request->measurement_category,
                'Measurement_name' => $name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with('success', 'Measurements Added Successfully');
    }

    public function view_measurement_parts(Request $request)
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $MeasurementPart = MeasurementPart::where('admin_or_user_id', $userId)->paginate(10); // Adjust according to your database structure
            return view('admin_panel.measurement_parts.view-measurement-parts', [
                'MeasurementPart' => $MeasurementPart,
            ]);
        } else {
            return redirect()->back();
        }
    }
}
