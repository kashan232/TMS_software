<?php

use App\Http\Controllers\ClothTypeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeasurementPartController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ExpenseManageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
// Route::get('/adminpage', [HomeController::class, 'adminpage'])->middleware(['auth','admin'])->name('adminpage');


//cloth-type
Route::get('/cloth-type', [ClothTypeController::class, 'cloth_type'])->middleware(['auth','admin'])->name('cloth-type');
Route::post('/store-cloth-type', [ClothTypeController::class, 'store_cloth_type'])->name('store-cloth-type');
Route::post('/cloth-type/update', [ClothTypeController::class, 'update'])->name('cloth_type.update');

// measurment
Route::get('/create-measurement-parts', [MeasurementPartController::class, 'create_measurement_parts'])->middleware(['auth','admin'])->name('create-measurement-parts');
Route::post('/measurment_store', [MeasurementPartController::class, 'measurment_store'])->name('measurment_store');
Route::get('/view-measurement-parts', [MeasurementPartController::class, 'view_measurement_parts'])->middleware(['auth','admin'])->name('view-measurement-parts');


//cloth-type
Route::get('/price-list', [PriceListController::class, 'price_list'])->middleware(['auth','admin'])->name('price-list');
Route::post('/store-price-list', [PriceListController::class, 'store_price_list'])->name('store-price-list');
Route::post('/price-list/update', [PriceListController::class, 'update'])->name('price-list.update');

//staff
Route::get('/designations', [StaffController::class, 'designations'])->middleware(['auth','admin'])->name('designations');
Route::post('/store-designations', [StaffController::class, 'store_designations'])->name('store-designations');
Route::post('/designations/update', [StaffController::class, 'update'])->name('designations.update');

Route::get('/add-staff', [StaffController::class, 'add_staff'])->middleware(['auth','admin'])->name('add-staff');
Route::post('/store-staff', [StaffController::class, 'store_staff'])->name('store-staff');
Route::get('/staffs', [StaffController::class, 'staffs'])->middleware(['auth','admin'])->name('staffs');
Route::post('/staffs/update', [StaffController::class, 'update_staff'])->name('staffs.update');

Route::get('/staff-expenses', [StaffController::class, 'staff_expenses'])->name('staff.expenses.index');
Route::post('/staff-expenses-store', [StaffController::class, 'store_expense'])->name('staff-expenses.store');
Route::get('/get-previous-balance/{staff_id}', [StaffController::class, 'getPreviousBalance'])->name('staff.previous_balance');
Route::get('/staff-expenses-record', [StaffController::class, 'staff_expenses_record'])->name('staff-expenses-record');

// Expenses
Route::get('/add-expense-category', [ExpenseManageController::class, 'add_expense_category'])->middleware(['auth','admin'])->name('add-expense-category');
Route::post('/store-category', [ExpenseManageController::class, 'store_category'])->name('store-category');
Route::get('/expenses', [ExpenseManageController::class, 'view_expenses'])->middleware(['auth','admin'])->name('expenses');
Route::get('/add-expenses', [ExpenseManageController::class, 'add_expenses'])->middleware(['auth','admin'])->name('add-expenses');
Route::post('/store-expenses', [ExpenseManageController::class, 'store_expenses'])->name('store-expenses');
// Customer
Route::get('/add-Customer', [CustomerController::class, 'add_Customer'])->middleware(['auth','admin'])->name('add-Customer');
Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
Route::get('/Customers', [CustomerController::class, 'Customers'])->middleware(['auth','admin'])->name('Customers');
Route::post('/update-customer', [CustomerController::class, 'updateCustomer'])->name('update-customer');
Route::get('/customer-add-measurement/{id}', [CustomerController::class, 'customer_add_measurement'])->middleware(['auth','admin'])->name('customer-add-measurement');
Route::get('/fetch-measurement-parts', [CustomerController::class, 'fetchMeasurementParts'])->name('fetch-measurement-parts');
Route::post('/measurements/{customerId}', [CustomerController::class, 'customer_measruemt_store'])->name('measurements.store');
Route::get('/customer/send-email/{id}', [CustomerController::class, 'showEmailForm'])->name('customer.send-email');
Route::post('/customer/send-email', [CustomerController::class, 'sendEmail'])->name('customer.send-email.post');


// Order management
Route::get('/add-Order', [OrderManagementController::class, 'add_Order'])->middleware(['auth','admin'])->name('add-Order');
Route::post('/Order/store', [OrderManagementController::class, 'saveOrder'])->name('Order.store');
Route::get('/Orders', [OrderManagementController::class, 'Orders'])->middleware(['auth','admin'])->name('Orders');
Route::get('/order/receipt/{id}', [OrderManagementController::class, 'showReceipt'])->name('order.receipt');
Route::post('/payment/update', [OrderManagementController::class, 'updatePayment'])->name('payment.update');
Route::get('/order/edit/{id}', [OrderManagementController::class, 'editOrder'])->name('order.edit');
Route::post('/orders/{order}', [OrderManagementController::class, 'updateOrder'])->name('orders.update');
Route::get('/Orders-tracker', [OrderManagementController::class, 'Orders_tracker'])->name('Orders-tracker');
Route::get('/Orders-tracker/view/{id}', [OrderManagementController::class, 'viewOrder'])->name('order.track.view');


Route::get('/order-calender', [OrderManagementController::class, 'order_calender'])->name('order-calender');
Route::post('/update-order-status', [OrderManagementController::class, 'updateStatus'])->name('update.order.status');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
