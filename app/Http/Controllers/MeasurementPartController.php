<?php

namespace App\Http\Controllers;

use App\Models\ClothType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
