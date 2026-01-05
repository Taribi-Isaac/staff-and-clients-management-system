<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $category = $request->input('category');
        $status = $request->input('status');

        $items = Item::when($query, function ($q) use ($query) {
            $q->where('item_name', 'LIKE', "%{$query}%")
              ->orWhere('item_code', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        })
        ->when($category, function ($q) use ($category) {
            $q->where('category_id', $category);
        })
        ->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })
        ->with(['category', 'supplier', 'creator'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        $categories = Category::orderBy('name')->get();
        return view('items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('supplier_name')->get();
        // Pass categories as JSON for JavaScript
        $categoriesJson = $categories->mapWithKeys(function ($category) {
            return [$category->id => ['prefix' => $category->code_prefix ?? '', 'name' => $category->name]];
        })->toJson();
        return view('items.create', compact('categories', 'suppliers', 'categoriesJson'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'item_code' => 'nullable|string|max:255|unique:items,item_code',
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_consumable' => 'boolean',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['is_consumable'] = $request->has('is_consumable');

        // Auto-generate item code if not provided and category is selected
        if (empty($data['item_code']) && !empty($data['category_id'])) {
            $data['item_code'] = $this->generateItemCode($data['category_id']);
        }

        $item = Item::create($data);
        $item->updateStockStatus();

        return redirect()->route('items.index')
            ->with('success', 'Item created successfully!');
    }

    /**
     * Generate a unique item code based on category prefix
     */
    private function generateItemCode($categoryId): string
    {
        $category = Category::find($categoryId);
        if (!$category || empty($category->code_prefix)) {
            // If no category or prefix, use generic prefix
            $prefix = 'ITM';
        } else {
            $prefix = strtoupper($category->code_prefix);
        }

        // Get the last item with this prefix
        $lastItem = Item::where('item_code', 'LIKE', $prefix . '-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(item_code, "-", -1) AS UNSIGNED) DESC')
            ->first();

        if ($lastItem && preg_match('/' . preg_quote($prefix, '/') . '-(\d+)$/', $lastItem->item_code, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        $code = $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Ensure uniqueness
        $counter = 1;
        while (Item::where('item_code', $code)->exists()) {
            $code = $prefix . '-' . str_pad($nextNumber + $counter, 4, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $code;
    }

    public function show(string $id)
    {
        $item = Item::with(['category', 'supplier', 'creator', 'transactions.assignedUser', 'transactions.assignedClient', 'transactions.assignedProject'])
            ->findOrFail($id);
        return view('items.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('supplier_name')->get();
        // Pass categories as JSON for JavaScript
        $categoriesJson = $categories->mapWithKeys(function ($category) {
            return [$category->id => ['prefix' => $category->code_prefix ?? '', 'name' => $category->name]];
        })->toJson();
        return view('items.edit', compact('item', 'categories', 'suppliers', 'categoriesJson'));
    }

    public function update(Request $request, string $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'item_name' => 'required|string|max:255',
            'item_code' => 'nullable|string|max:255|unique:items,item_code,' . $id,
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_consumable' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_consumable'] = $request->has('is_consumable');

        $item->update($data);
        $item->updateStockStatus();

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully!');
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized action.');
        }

        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully!');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $imported = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            if (($handle = fopen($path, 'r')) !== false) {
                // Skip header row
                $header = fgetcsv($handle);
                
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 3) {
                        $skipped++;
                        continue;
                    }

                    try {
                        // Expected CSV format: item_name, item_code, category_name, supplier_name, quantity, unit, unit_price, purchase_date, location, is_consumable, description
                        $itemData = [
                            'item_name' => $row[0] ?? '',
                            'item_code' => !empty($row[1]) ? $row[1] : null,
                            'quantity' => (int)($row[4] ?? 0),
                            'unit' => $row[5] ?? 'piece',
                            'unit_price' => !empty($row[6]) ? (float)$row[6] : 0,
                            'purchase_date' => !empty($row[7]) ? $row[7] : null,
                            'location' => $row[8] ?? null,
                            'is_consumable' => !empty($row[9]) && (strtolower($row[9]) === 'yes' || $row[9] === '1' || strtolower($row[9]) === 'true'),
                            'description' => $row[10] ?? null,
                            'min_stock_level' => 0,
                            'created_by' => auth()->id(),
                        ];

                        // Find or create category
                        if (!empty($row[2])) {
                            $category = Category::firstOrCreate(
                                ['name' => trim($row[2])],
                                ['description' => null]
                            );
                            $itemData['category_id'] = $category->id;
                        }

                        // Find or create supplier
                        if (!empty($row[3])) {
                            $supplier = Supplier::firstOrCreate(
                                ['supplier_name' => trim($row[3])],
                                [
                                    'contact_person' => null,
                                    'email' => null,
                                    'phone' => null,
                                    'address' => null,
                                ]
                            );
                            $itemData['supplier_id'] = $supplier->id;
                        }

                        if (empty($itemData['item_name'])) {
                            $skipped++;
                            continue;
                        }

                        $item = Item::create($itemData);
                        $item->updateStockStatus();
                        $imported++;
                    } catch (\Exception $e) {
                        $skipped++;
                        $errors[] = "Row error: " . $e->getMessage();
                        Log::warning('CSV import error', ['error' => $e->getMessage(), 'row' => $row]);
                    }
                }
                fclose($handle);
            }

            DB::commit();

            $message = "Successfully imported {$imported} items";
            if ($skipped > 0) {
                $message .= ", skipped {$skipped} rows";
            }

            return redirect()->route('items.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk upload error', ['error' => $e->getMessage()]);
            
            return redirect()->route('items.index')
                ->with('error', 'Failed to import items: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filename = 'inventory_bulk_upload_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'item_name',
                'item_code',
                'category_name',
                'supplier_name',
                'quantity',
                'unit',
                'unit_price',
                'purchase_date',
                'location',
                'is_consumable',
                'description'
            ]);

            // Example rows
            fputcsv($file, [
                'Laptop Computer',
                'LAP-001',
                'Electronics',
                'Tech Supplier Ltd',
                '10',
                'piece',
                '50000',
                '2025-01-05',
                'Warehouse A',
                'no',
                'Dell Laptop 15 inch'
            ]);

            fputcsv($file, [
                'USB Cable',
                'CBL-001',
                'Electronics',
                'Tech Supplier Ltd',
                '100',
                'piece',
                '500',
                '2025-01-05',
                'Warehouse A',
                'yes',
                'USB Type C Cable'
            ]);

            fputcsv($file, [
                'Office Chair',
                'CHR-001',
                'Furniture',
                'Furniture World',
                '20',
                'piece',
                '15000',
                '2025-01-05',
                'Warehouse B',
                'no',
                'Ergonomic Office Chair'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
