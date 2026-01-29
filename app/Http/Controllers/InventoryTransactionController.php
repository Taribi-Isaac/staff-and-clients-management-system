<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Models\User;
use App\Models\Clients;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $type = $request->input('type');

        $transactions = InventoryTransaction::when($query, function ($q) use ($query) {
            $q->whereHas('item', function ($itemQuery) use ($query) {
                $itemQuery->where('item_name', 'LIKE', "%{$query}%");
            });
        })
        ->when($type, function ($q) use ($type) {
            $q->where('transaction_type', $type);
        })
        ->with(['item', 'assignedUser', 'assignedClient', 'assignedProject', 'creator'])
        ->orderBy('transaction_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('inventory-transactions.index', compact('transactions'));
    }

    public function create()
    {
        $items = Item::orderBy('item_name')->get();
        $users = User::orderBy('name')->get();
        $clients = Clients::orderBy('client_name')->get();
        $projects = Project::orderBy('project_name')->get();
        return view('inventory-transactions.create', compact('items', 'users', 'clients', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'transaction_type' => 'required|in:purchase,assignment,return,adjustment,consumption',
            'quantity' => 'required|integer|min:1',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'assigned_to_client_id' => 'nullable|exists:clients,id',
            'assigned_to_project_id' => 'nullable|exists:projects,id',
            'assigned_to_external_individual' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'expected_return_date' => 'nullable|date|after_or_equal:transaction_date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($request->item_id);

            // Calculate stock change based on transaction type
            // purchase, return, adjustment (positive) -> increases stock
            // assignment, consumption (negative) -> decreases stock
            $stockChange = 0;
            
            if ($request->transaction_type === 'purchase') {
                // Purchase increases stock
                $stockChange = abs($request->quantity);
            } elseif ($request->transaction_type === 'return') {
                // Return increases stock (item is being returned to inventory)
                $stockChange = abs($request->quantity);
            } elseif ($request->transaction_type === 'adjustment') {
                // Adjustment can be positive or negative (handled by quantity input sign)
                $stockChange = $request->quantity;
            } elseif ($request->transaction_type === 'assignment') {
                // Assignment decreases stock (item assigned to staff/client/project/external individual)
                $stockChange = -abs($request->quantity);
                // Require assignment target for assignment type
                if (!$request->assigned_to_user_id && !$request->assigned_to_client_id && !$request->assigned_to_project_id && !$request->assigned_to_external_individual) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Assignment requires a staff, client, project, or external individual to be assigned to.');
                }
            } elseif ($request->transaction_type === 'consumption') {
                // Consumption decreases stock permanently (for consumables)
                $stockChange = -abs($request->quantity);
            }

            // Check if we have enough stock for assignments/consumption
            $newQuantity = $item->quantity + $stockChange;
            if ($newQuantity < 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient stock! Available: ' . $item->quantity . ' ' . $item->unit);
            }

            // Create transaction
            $transaction = InventoryTransaction::create([
                'item_id' => $request->item_id,
                'transaction_type' => $request->transaction_type,
                'quantity' => abs($request->quantity),
                'assigned_to_user_id' => $request->assigned_to_user_id,
                'assigned_to_client_id' => $request->assigned_to_client_id,
                'assigned_to_project_id' => $request->assigned_to_project_id,
                'assigned_to_external_individual' => $request->assigned_to_external_individual,
                'transaction_date' => $request->transaction_date,
                'expected_return_date' => $request->expected_return_date,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            // Update item quantity
            $item->quantity = max(0, $newQuantity);
            $item->updateStockStatus();

            DB::commit();

            return redirect()->route('inventory-transactions.index')
                ->with('success', 'Transaction created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $transaction = InventoryTransaction::with(['item', 'assignedUser', 'assignedClient', 'assignedProject', 'creator'])
            ->findOrFail($id);
        return view('inventory-transactions.show', compact('transaction'));
    }
}
