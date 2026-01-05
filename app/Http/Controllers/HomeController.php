<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Employees;
use App\Models\Issues;
use App\Models\Staff;
use App\Models\User;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    
    public function index(){
        // Basic counts
        $allClients = Clients::count();
        $allIssues = Issues::count();
        $allStaff = Staff::count();
        $allEmployees = Employees::count();
        
        // Projects statistics
        $allProjects = Project::count();
        $activeProjects = Project::where('status', 'in_progress')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $totalProjectBudget = Project::sum('budget');
        $totalProjectCost = Project::sum('actual_cost');
        
        // Invoices statistics
        $allInvoices = Invoice::count();
        $totalInvoices = Invoice::where('type', 'invoice')->count();
        $totalReceipts = Invoice::where('type', 'receipt')->count();
        $totalQuotes = Invoice::where('type', 'quote')->count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $totalRevenue = Invoice::where('status', 'paid')->sum('total');
        $pendingInvoices = Invoice::where('status', 'sent')->count();
        
        // Inventory statistics
        $allItems = Item::count();
        $lowStockItems = Item::where('status', 'low_stock')->count();
        $outOfStockItems = Item::where('status', 'out_of_stock')->count();
        $totalSuppliers = Supplier::count();
        $totalCategories = Category::count();
        $totalTransactions = InventoryTransaction::count();
        $totalInventoryValue = Item::selectRaw('SUM(quantity * unit_price) as total')->value('total') ?? 0;
        
        // Employee/Staff statistics
        $activeEmployees = Employees::where('status', 'active')->count();
        $activeStaff = Staff::where('status', 'active')->count();
        
        // Client statistics
        $activeClients = Clients::where('status', 'active')->count();
        $inactiveClients = Clients::where('status', 'inactive')->count();
        
        // Recent activity (last 7 days)
        $recentProjects = Project::where('created_at', '>=', now()->subDays(7))->count();
        $recentInvoices = Invoice::where('created_at', '>=', now()->subDays(7))->count();
        $recentClients = Clients::where('created_at', '>=', now()->subDays(7))->count();
        $recentTransactions = InventoryTransaction::where('created_at', '>=', now()->subDays(7))->count();

        return view('home', compact(
            'allClients', 'allIssues', 'allStaff', 'allEmployees',
            'allProjects', 'activeProjects', 'completedProjects', 'totalProjectBudget', 'totalProjectCost',
            'allInvoices', 'totalInvoices', 'totalReceipts', 'totalQuotes', 'paidInvoices', 'totalRevenue', 'pendingInvoices',
            'allItems', 'lowStockItems', 'outOfStockItems', 'totalSuppliers', 'totalCategories', 'totalTransactions', 'totalInventoryValue',
            'activeEmployees', 'activeStaff', 'activeClients', 'inactiveClients',
            'recentProjects', 'recentInvoices', 'recentClients', 'recentTransactions'
        ));
    }
}
