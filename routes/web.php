<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\IssuesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\CashBookController;
use App\Http\Controllers\PettyCashController;
use App\Http\Controllers\SalesBookController;
use App\Http\Controllers\PurchasesBookController;
use App\Http\Controllers\ArLedgerController;
use App\Http\Controllers\ApLedgerController;
use App\Http\Controllers\PayrollBookController;
use App\Http\Controllers\GeneralLedgerController;
use App\Models\Issues;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public employee registration form
Route::get('/employee/register', [EmployeesController::class, 'publicCreate'])->name('employee.public.create');
Route::post('/employee/register', [EmployeesController::class, 'publicStore'])->name('employee.public.store');

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile/view', [ProfileController::class, 'profile'])->name('profile.profile');
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients/store', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/issues', [IssuesController::class, 'index'])->name('issues.index');
    Route::get('/issues/create', [IssuesController::class, 'create'])->name('issues.create');
    Route::post('/issues/store', [IssuesController::class, 'store'])->name('issues.store');
    Route::get('/issues/{id}', [IssuesController::class, 'show'])->name('issues.show');
    Route::post('/issues/{issue}', [IssuesController::class, 'update'])->name('issues.update');


    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::post('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

    Route::get('/employees/create', [EmployeesController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeesController::class, 'store'])->name('employees.store');
    Route::get('/employees', [EmployeesController::class, 'index'])->name('employees.index');
    Route::get('/employees/{id}', [EmployeesController::class, 'show'])->name('employees.show');
    Route::get('/employees/{id}/edit', [EmployeesController::class, 'edit'])->name('employees.edit');
    Route::post('/employees/{employees}', [EmployeesController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeesController::class, 'destroy'])->name('employees.destroy');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');

    Route::post('/admin/{admin}', [AdminController::class, 'update'])->name('admin.update');

    Route::delete('/admin/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');

    // Invoice Routes
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{id}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoices.duplicate');
    Route::get('/invoices/{id}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');

    // Project Routes
    Route::resource('projects', ProjectController::class);

    // Inventory Routes
    Route::resource('items', ItemController::class);
    Route::post('/items/bulk-upload', [ItemController::class, 'bulkUpload'])->name('items.bulk-upload');
    Route::get('/items/template/download', [ItemController::class, 'downloadTemplate'])->name('items.template.download');
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('inventory-transactions', InventoryTransactionController::class)->except(['edit', 'update', 'destroy']);

    // Finance Routes
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/general-ledger', [GeneralLedgerController::class, 'index'])->name('general-ledger.index');
    Route::get('/general-ledger/export', [GeneralLedgerController::class, 'export'])->name('general-ledger.export');
    Route::resource('cash-book', CashBookController::class);
    Route::post('/petty-cash/{id}/authorize', [PettyCashController::class, 'authorize'])->name('petty-cash.authorize');
    Route::get('/petty-cash/employee/{employeeId}', [PettyCashController::class, 'employeeTransactions'])->name('petty-cash.employee');
    Route::get('/petty-cash/employee/{employeeId}/export', [PettyCashController::class, 'exportEmployeeTransactions'])->name('petty-cash.employee.export');
    Route::resource('petty-cash', PettyCashController::class);
    Route::resource('sales-book', SalesBookController::class);
    Route::resource('purchases-book', PurchasesBookController::class);
    Route::resource('ar-ledger', ArLedgerController::class);
    Route::resource('ap-ledger', ApLedgerController::class);
    Route::resource('payroll-book', PayrollBookController::class);
    
    // Export routes for financial books
    Route::get('/cash-book/export', [CashBookController::class, 'export'])->name('cash-book.export');
    Route::get('/petty-cash/export', [PettyCashController::class, 'export'])->name('petty-cash.export');
    Route::get('/sales-book/export', [SalesBookController::class, 'export'])->name('sales-book.export');
    Route::get('/purchases-book/export', [PurchasesBookController::class, 'export'])->name('purchases-book.export');
    Route::get('/ar-ledger/export', [ArLedgerController::class, 'export'])->name('ar-ledger.export');
    Route::get('/ap-ledger/export', [ApLedgerController::class, 'export'])->name('ap-ledger.export');
    Route::get('/payroll-book/export', [PayrollBookController::class, 'export'])->name('payroll-book.export');
});

require __DIR__ . '/auth.php';
