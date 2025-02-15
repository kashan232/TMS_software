<?php

namespace App\Http\Controllers;

use App\Mail\OrderReadyMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderAssignment;
use App\Models\OrderTracking;
use App\Models\Staff;
use App\Models\StaffDesignation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

            $Orders = Order::where('admin_or_user_id', $userId)
                ->with('customer') // Ye customer data load karega
                ->paginate(10);
            $staffMembers = Staff::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            // dd($Orders); // Check karein ke email aa raha hai ya nahi

            return view('admin_panel.order.orders', [
                'Orders' => $Orders,
                'staffMembers' => $staffMembers,
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

    public function order_calender()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            return view('admin_panel.order.order_calender', []);
        } else {
            return redirect()->back();
        }
    }

    public function Orders_tracker()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $Orders = Order::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            // dd($Orders);
            return view('admin_panel.order.orders_tracker', [
                'Orders' => $Orders,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function viewOrder($id)
    {
        $order = Order::with('customer')->findOrFail($id);
        // Decode arrays
        $order->cloth_types = json_decode($order->cloth_type, true);
        $order->quantities = json_decode($order->quantity, true);
        $order->prices = json_decode($order->price, true);
        $order->item_totals = json_decode($order->item_total, true);

        // Tracking Status Fetch Karo
        $trackingStatuses = OrderTracking::where('order_id', $id)
            ->pluck('status', 'cloth_type')
            ->toArray();

        return view('admin_panel.order.order_detail', compact('order', 'trackingStatuses'));
    }

    public function orderupdateStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $order->status = $request->status;
        $order->delivery_description = $request->description;
        $order->save();

        // Agar order "Ready" ho gaya to customer ko email bhejo
        if ($order->status == "Ready" && $order->customer) {
            Mail::to($order->customer->email)->send(new OrderReadyMail($order));
        }

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Jo cloth type track ho raha hai uska data save karo
        OrderTracking::updateOrCreate(
            [
                'order_id' => $request->order_id,
                'cloth_type' => $request->cloth_type,
            ],
            [
                'quantity' => $request->quantity,
                'status' => $request->order_status,
            ]
        );

        return response()->json(['success' => 'Order status updated successfully']);
    }

    public function getOrders(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        // Fetch orders based on the month and year
        $orders = Order::whereYear('collection_date', $year) // Filter based on collection date year
            ->whereMonth('collection_date', $month) // Filter based on collection date month
            ->get();

        // Group orders by collection date (day)
        $groupedOrders = $orders->groupBy(function ($order) {
            return Carbon::parse($order->collection_date)->day; // Group by the day of collection date
        });

        // Prepare the result with customer number and status per day
        $formattedOrders = [];

        foreach ($groupedOrders as $day => $ordersForDay) {
            $formattedOrders[$day] = $ordersForDay->map(function ($order) {
                return [
                    'customer_number' => $order->customer_number,
                    'status' => $order->status, // You can add more fields if needed
                    'collection_date' => $order->collection_date
                ];
            });
        }

        // Returning the orders grouped by day with customer number and status
        return response()->json($formattedOrders);
    }

    public function Orders_asign()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $customers = Customer::where('admin_or_user_id', $userId)->get();
            $StaffDesignations = StaffDesignation::where('admin_or_user_id', $userId)->get();
            return view('admin_panel.order.orders_asign', ['customers' => $customers, 'StaffDesignations' => $StaffDesignations]);
        } else {
            return redirect()->back();
        }
    }

    public function fetchOrders(Request $request)
    {
        $orders = Order::where('customer_number', $request->customer_number)->where('status', 'Order Received')->get();
        return response()->json(['orders' => $orders]);
    }

    public function fetchStaff(Request $request)
    {
        $staff = Staff::where('designations', $request->designation)->get();
        return response()->json(['staff' => $staff]);
    }

    public function assignOrders(Request $request)
    {
        $validated = $request->validate([
            'customer_number' => 'required',
            'hidden_asign_date' => 'required',
            'order_id' => 'required',
            'cloth_type' => 'required',
            'staff_id' => 'required',
        ]);

        foreach ($validated['order_id'] as $index => $orderId) {
            $order = Order::find($orderId);

            if ($order) {
                $staff = Staff::find($validated['staff_id'][$index]);

                // Assign Order to Staff
                OrderAssignment::create([
                    'customer_number' => $validated['customer_number'],
                    'asign_date' => $validated['hidden_asign_date'],
                    'order_id' => $orderId,
                    'cloth_type' => $validated['cloth_type'][$index],
                    'staff_id' => $validated['staff_id'][$index],
                    'assigned_by' => auth()->id(),
                ]);

                // Update Order Status and Assigned Name
                $order->update([
                    'status' => 'Assigned',
                    'assign_name' => $staff ? $staff->full_name : null,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Orders assigned successfully!']);
    }

    public function staff_order_asign()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $StaffDesignations = StaffDesignation::where('admin_or_user_id', $userId)->get();
            return view('admin_panel.order.staff_order_asign', ['StaffDesignations' => $StaffDesignations]);
        } else {
            return redirect()->back();
        }
    }

    public function fetchAssignedOrders(Request $request)
    {
        $orders = OrderAssignment::where('staff_id', $request->staff_id)
            ->whereHas('order', function ($query) {
                $query->where('status', 'Assigned'); // Filter orders with status "Assigned"
            })
            ->with(['order' => function ($query) {
                $query->select('id', 'customer_number', 'cloth_type', 'price', 'quantity', 'item_total', 'status');
            }])
            ->select('id', 'order_id', 'staff_id', 'asign_date') // 👈 Assign Date ko select karna zaroori hai
            ->get();

        return response()->json(['orders' => $orders]);
    }
    // Update order status to "Received from Staff"
    public function receiveOrder(Request $request)
    {
        foreach ($request->order_id as $orderId) {
            Order::where('id', $orderId)->update(['status' => 'Received from Staff']);
        }
        return response()->json(['message' => 'Orders received successfully.']);
    }
}
