<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Expense;
use App\Models\AddExpenses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ExpenseManageController extends Controller
{
    public function add_expense_category()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $Expense = Expense::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure
            return view('admin_panel.Expense_management.add_expense_category', [
                'Expense' => $Expense,
            ]);
        } else {
            return redirect()->back();
        }
    }
    public function store_category(Request $request)
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();
            Expense::create([
                'admin_or_user_id'    => $userId,
                'category_name'          => $request->category_name,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'ClothType has been  created successfully');
        } else {
            return redirect()->back();
        }
    }

    public function view_expenses(Request $request)
    {
        if (Auth::id()) {
            $userId = Auth::id();

            $view_expenses = Expense::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            return view('admin_panel.Expense_management.view_expense', [
                'view_expenses' => $view_expenses,
            ]);
        } else {
            return redirect()->back();
        }
    }



    public function staff_expenses()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $staffMembers = Staff::where('admin_or_user_id', $userId)->get(); // Adjust according to your database structure

            // $expenses = StaffExpense::with('staff')->orderBy('created_at', 'desc')->get();
            return view('admin_panel.staff_management.staff_expenses', compact('staffMembers'));
        } else {
            return redirect()->back();
        }
    }

    public function add_expenses()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            // Fetching data from the Expense model
            $categories = Expense::where('admin_or_user_id', $userId)->pluck('category_name', 'id'); // Fetch category_name and id
            return view('admin_panel.Expense_management.add_expenses', compact('categories'));
        } else {
            return redirect()->back();
        }
    }

    public function store_expenses(Request $request)
    {

        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();
            AddExpenses::create([
                'admin_or_user_id'    => $userId,
                'category_id'       => $request->category_id, // ✅ Match with DB
                'title'               => $request->title,
                'expense_amount'      => $request->expense_amount,
                'date'                => $request->date,
                'description'         => $request->description,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            return redirect()->back()->with('success', 'Expense has been created successfully');
        } else {
            return redirect()->back();
        }
    }


    public function editexpenses($id)
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $expense = AddExpenses::findOrFail($id);
            $categories = Expense::where('admin_or_user_id', $userId)->pluck('category_name', 'id');

            return view('admin_panel.Expense_management.edit_expenses', compact('expense', 'categories'));
        } else {
            return redirect()->back();
        }
    }

    public function updateExpense(Request $request, $id)
    {
        if (Auth::id()) {
            $expense = AddExpenses::findOrFail($id);
            $expense->update([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'expense_amount' => $request->expense_amount,
                'date' => $request->date,
                'description' => $request->description
            ]);

            return redirect()->back()->with('success', 'Expense updated successfully');
        } else {
            return redirect()->back();
        }
    }

    public function deleteExpense(Request $request)
    {
        try {
            $expense = AddExpenses::findOrFail($request->id);
            $expense->delete();

            return response()->json(['success' => true, 'message' => 'Expense deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting expense: ' . $e->getMessage()]);
        }
    }


    public function view_all_expenses(Request $request)
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $view_expenses = AddExpenses::where('admin_or_user_id', $userId)
                ->with('category') // Category relation load karein
                ->get();
            $categories = Expense::where('admin_or_user_id', $userId)->get(); // Fetch category_name and id
            // dd($categories);

            return view('admin_panel.Expense_management.view_all_expense', [
                'view_expenses' => $view_expenses,
                'categories' => $categories,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function updateCategory(Request $request)
    {
        $category = Expense::find($request->id);

        if ($category) {
            $category->category_name = $request->category_name;
            $category->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }



    public function destroy($id)
    {
        $expense = AddExpenses::findOrFail($id);
        $expense->delete();

        return response()->json(['success' => true]);
    }
}
