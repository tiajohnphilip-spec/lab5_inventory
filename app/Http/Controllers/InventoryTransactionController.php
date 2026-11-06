<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\InventoryTransaction;
use App\Models\Product;

class InventoryTransactionController extends Controller
{
    // Display transaction history with filters
    public function index(Request $request)
    {
        $query = InventoryTransaction::with(['product', 'user']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $transactions = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $products = Product::orderBy('name')->get();

        return view('transactions.index', compact(
            'transactions',
            'products'
        ));
    }

    // Show transaction creation form
    public function create()
    {
        $products = Product::orderBy('name')->get();

        $types = [
            'stock_in',
            'stock_out',
            'adjustment'
        ];

        return view('transactions.create', compact(
            'products',
            'types'
        ));
    }

    // Store a new transaction
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:stock_in,stock_out,adjustment',
            'quantity' => 'required|integer',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if (
            $validated['type'] !== 'adjustment'
            && $validated['quantity'] < 1
        ) {
            throw ValidationException::withMessages([
                'quantity' => 'Quantity must be at least 1.'
            ]);
        }

        if (
            $validated['type'] === 'adjustment'
            && $validated['quantity'] == 0
        ) {
            throw ValidationException::withMessages([
                'quantity' => 'Adjustment quantity cannot be zero.'
            ]);
        }

        DB::transaction(function () use ($validated) {
            InventoryTransaction::create([
                'product_id' => $validated['product_id'],
                'user_id' => auth()->id(),
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Inventory transaction recorded successfully.');
    }

    // Display transaction details
    public function show(string $id)
    {
        $transaction = InventoryTransaction::with([
            'product',
            'user'
        ])->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    // Editing transactions is disabled
    public function edit(string $id)
    {
        abort(404);
    }

    public function update(Request $request, string $id)
    {
        abort(404);
    }

    // Delete transaction with stock reversal (admin only)
    public function destroy(string $id)
    {
        abort_unless(
            auth()->user()->role === 'admin',
            403
        );

        DB::transaction(function () use ($id) {
            $transaction = InventoryTransaction::lockForUpdate()
                ->findOrFail($id);

            $product = Product::where(
                'id',
                $transaction->product_id
            )->lockForUpdate()->firstOrFail();

            $quantity = (int) $transaction->quantity;

            if ($transaction->type === 'stock_in') {
                $newQuantity = $product->quantity - $quantity;
            } elseif ($transaction->type === 'stock_out') {
                $newQuantity = $product->quantity + $quantity;
            } elseif ($transaction->type === 'adjustment') {
                $newQuantity = $product->quantity - $quantity;
            } else {
                abort(422, 'Invalid transaction type.');
            }

            if ($newQuantity < 0) {
                throw ValidationException::withMessages([
                    'transaction' => 'Cannot delete this transaction because it would result in negative stock.'
                ]);
            }

            $product->quantity = $newQuantity;
            $product->save();

            $transaction->delete();
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction deleted and stock reversed successfully.');
    }
}
