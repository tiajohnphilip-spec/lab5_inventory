<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryAdjustmentController extends Controller
{
    private function authorizeAdjustment(): void
    {
        abort_unless(
            auth()->check() &&
            in_array(auth()->user()->role, ['admin', 'manager']),
            403
        );
    }

    public function create()
    {
        $this->authorizeAdjustment();

        $products = Product::orderBy('name')->get();

        return view('adjustments.create', compact('products'));
    }

    public function product(Product $product)
    {
        $this->authorizeAdjustment();

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => $product->quantity,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdjustment();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'physical_count' => 'required|integer|min:0',
            'reason' => 'required|in:physical_count_discrepancy,damaged_goods,theft_loss,system_error',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $difference = (int) $validated['physical_count']
                - (int) $product->quantity;

            if ($difference === 0) {
                throw ValidationException::withMessages([
                    'physical_count' => 'No adjustment needed. The physical count matches the system quantity.',
                ]);
            }

            InventoryTransaction::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'adjustment',
                'quantity' => $difference,
                'reference_number' => 'ADJ-' . now()->format('YmdHis'),
                'notes' => 'Reason: ' . $validated['reason']
                    . '. ' . ($validated['notes'] ?? ''),
            ]);
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Stock adjustment completed successfully.');
    }
}
