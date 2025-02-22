<?php

namespace App\Http\Controllers;

use App\Models\ClothType;
use App\Models\ClothVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClothTypeController extends Controller
{
    public function cloth_type()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $ClothTypes = ClothType::where('admin_or_user_id', $userId)->paginate(10); // Adjust according to your database structure

            return view('admin_panel.cloth_type.cloth_types', [
                'ClothTypes' => $ClothTypes,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function store_cloth_type(Request $request)
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();
            ClothType::create([
                'admin_or_user_id'    => $userId,
                'cloth_type_name'          => $request->cloth_type_name,
                'cloth_type_gender'          => $request->cloth_type_gender,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'ClothType has been  created successfully');
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        // Get the cloth type ID from the request
        $cloth_type_id = $request->input('cloth_type_id');
        // dd($cloth_type_id);

        // Update the cloth type in the database
        ClothType::where('id', $cloth_type_id)->update([
            'cloth_type_name' => $request->cloth_type_name,
            'cloth_type_gender' => $request->gender,
        ]);

        return redirect()->back()->with('success', 'Cloth Type updated successfully');
    }

   

    public function cloth_Variants()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $ClothTypes = ClothType::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            $ClothVariants = ClothVariant::where('admin_or_user_id', $userId)->paginate(10); // Adjust according to your database structure

            return view('admin_panel.cloth_type.cloth_variants', [
                'ClothTypes' => $ClothTypes,
                'ClothVariants' => $ClothVariants,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function store_cloth_Variants(Request $request)
    {
        $request->validate([
            'cloth_type_name' => 'required|string',
            'variants_name' => 'required|array',
            'variants_name.*' => 'string|min:1',
        ]);

        $userId = Auth::id();

        foreach ($request->variants_name as $name) {
            ClothVariant::create([
                'admin_or_user_id' => $userId,
                'cloth_type_name' => $request->cloth_type_name,
                'variants_name' => $name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with('success', 'variants Added Successfully');
    }

    public function delete_Variants(Request $request)
    {
        if (Auth::id()) {
            $ClothVariant = ClothVariant::find($request->id);

            if ($ClothVariant) {
                $ClothVariant->delete();
                return response()->json(['success' => true, 'message' => 'Cloth Variant deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Cloth Variant not found.']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized request.']);
    }
    
}
