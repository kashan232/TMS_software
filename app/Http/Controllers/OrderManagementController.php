<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderManagementController extends Controller
{
    public function add_Order()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            // Fetch customers for the authenticated user
            $customers = Customer::where('admin_or_user_id', $userId)->get();
            // dd($customers);
            // Fetch cloth types and their prices for the authenticated user
            $clothTypes = DB::table('cloth_types')
                ->join('price_lists', 'cloth_types.cloth_type_name', '=', 'price_lists.cloth_type_name')
                ->where('cloth_types.admin_or_user_id', $userId) // Specify table name for admin_or_user_id
                ->select(
                    'cloth_types.cloth_type_name',
                    'cloth_types.cloth_type_gender',
                    'price_lists.Price'
                )
                ->get();
            // dd($clothTypes);
            return view('admin_panel.order.create_order', [
                'customers' => $customers,
                'clothTypes' => $clothTypes,
            ]);
        } else {
            return redirect()->back();
        }
    }
    public function saveOrder(Request $request)
    {

        if (Auth::id()) {
            $userId = Auth::id();
            // Validate the incoming request
            $validatedData = $request->validate([
                'customer_number' => 'required',
                'order_receiving_date' => 'required|date',
                'order_received_by' => 'required',
                'order_description' => 'nullable|string',
                'cloth_type' => 'required|array',
                'price' => 'required|array',
                'quantity' => 'required|array',
                'item_total' => 'required|array',
                'sub_total' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'net_total' => 'required|numeric',
                'advance_paid' => 'nullable|numeric',
                'remaining' => 'required|numeric',
                'collection_date' => 'required|date',
            ]);

            // Create the order
            $order = new Order();
            $order->admin_or_user_id = $userId;
            $order->customer_number = $request->customer_number;
            $order->order_receiving_date = $request->order_receiving_date;
            $order->order_received_by = $request->order_received_by;
            $order->order_description = $request->order_description;
            $order->sub_total = $request->sub_total;
            $order->discount = $request->discount ?? 0;
            $order->net_total = $request->net_total;
            $order->advance_paid = $request->advance_paid ?? 0;
            $order->remaining = $request->remaining;
            $order->collection_date = $request->collection_date;
            $order->status = 'Order Received'; // Initial status

            // Encode individual arrays into JSON
            $order->cloth_type = json_encode($request->input('cloth_type', []));
            $order->price = json_encode($request->input('price', []));
            $order->quantity = json_encode($request->input('quantity', []));
            $order->item_total = json_encode($request->input('item_total', []));

            $order->save();

            return redirect()->back()->with('success', 'Order saved successfully!');
        } else {
            return redirect()->back();
        }
    }

    public function Orders()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $Orders = Order::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            // dd($Orders);
            return view('admin_panel.order.orders', [
                'Orders' => $Orders,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function showReceipt($id)
    {
        $order = Order::with('customer')->findOrFail($id);
        return view('admin_panel.order.receipt', compact('order'));
    }

    public function updatePayment(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        // Paid Amount
        $paid = $request->pay_amount;
        // Update Remaining
        $order->remaining -= $paid;
        // Update Advance Paid
        $order->advance_paid += $paid;
        $order->save();
        return response()->json(['message' => 'Payment updated successfully']);
    }

    public function editOrder($id)
    {
        $order = Order::with('customer')->findOrFail($id);
        // Decode the JSON-encoded strings into arrays
        $order->cloth_type = json_decode($order->cloth_type);
        $order->price = json_decode($order->price);
        $order->quantity = json_decode($order->quantity);
        $order->item_total = json_decode($order->item_total);

        // Fetch cloth types and their prices for the authenticated user
        $userId = Auth::id();
        $clothTypes = DB::table('cloth_types')
            ->join('price_lists', 'cloth_types.cloth_type_name', '=', 'price_lists.cloth_type_name')
            ->where('cloth_types.admin_or_user_id', $userId)
            ->select('cloth_types.cloth_type_name', 'price_lists.Price')
            ->get();

        return view('admin_panel.order.edit_order', compact('order', 'clothTypes'));
    }

    public function updateOrder(Request $request, $orderId)
    {
        if (Auth::id()) {
            $userId = Auth::id();
            dd($request);
            // Retrieve the existing order
            $order = Order::findOrFail($orderId);

            // Update the order fields
            $order->order_receiving_date = $request->order_receiving_date;
            $order->order_received_by = $request->order_received_by;
            $order->order_description = $request->order_description;
            $order->sub_total = $request->sub_total;
            $order->discount = $request->discount ?? 0;
            $order->net_total = $request->net_total;
            $order->advance_paid = $request->advance_paid ?? 0;
            $order->remaining = $request->remaining;
            $order->collection_date = $request->collection_date;
            // Keep the same order status unless you need to change it
            $order->status = 'Order Updated'; // Change status if necessary

            // Encode individual arrays into JSON
            $order->cloth_type = json_encode($request->input('cloth_type', []));
            $order->price = json_encode($request->input('price', []));
            $order->quantity = json_encode($request->input('quantity', []));
            $order->item_total = json_encode($request->input('item_total', []));

            // Save the updated order
            $order->save();

            return redirect()->back()->with('success', 'Order updated successfully!');

        } else {
            return redirect()->back();
        }
    }
}
