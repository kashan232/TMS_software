<?php

namespace App\Http\Controllers;

use App\Models\ClothType;
use App\Models\PriceList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceListController extends Controller
{
    public function price_list()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $PriceList = PriceList::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            $ClothTypes = ClothType::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            return view('admin_panel.price_list.price_lists', [
                'PriceList' => $PriceList,
                'ClothTypes' => $ClothTypes,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function store_price_list(Request $request)
    {
        if (Auth::id()) {
            // dd($request);
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();
            PriceList::create([
                'admin_or_user_id'    => $userId,
                'cloth_type_name'          => $request->cloth_type_name,
                'Price'          => $request->Price,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'PriceList has been  created successfully');
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        // Get the cloth type ID from the request
        $cloth_type_id = $request->input('edit_price_list_id');
        // dd($cloth_type_id);

        // Update the cloth type in the database
        PriceList::where('id', $cloth_type_id)->update([
            'cloth_type_name' => $request->cloth_type_name,
            'Price' => $request->Price,
        ]);

        return redirect()->back()->with('success', 'PriceList updated successfully');
    }
}
