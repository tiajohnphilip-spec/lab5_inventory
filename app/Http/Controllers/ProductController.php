<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'supplier' => 'required|string|max:255',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully.');
    }

    public function show(Request $request, Product $product)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Get all transactions in chronological order
        $allTransactions = $product->transactions()
            ->with('user')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        // Calculate total stock movement
        $netMovement = $allTransactions->sum(function ($transaction) {
            if ($transaction->type === 'stock_in') {
                return $transaction->quantity;
            }

            if ($transaction->type === 'stock_out') {
                return -$transaction->quantity;
            }

            if ($transaction->type === 'adjustment') {
                return $transaction->quantity;
            }

            return 0;
        });

        // Calculate opening balance
        $runningBalance = (int) $product->quantity - $netMovement;

        // Calculate running balance for each transaction
        foreach ($allTransactions as $transaction) {

            if ($transaction->type === 'stock_in') {
                $runningBalance += $transaction->quantity;
            } elseif ($transaction->type === 'stock_out') {
                $runningBalance -= $transaction->quantity;
            } elseif ($transaction->type === 'adjustment') {
                $runningBalance += $transaction->quantity;
            }

            $transaction->running_balance = $runningBalance;
        }

        // Apply date filters after calculating balances
        $transactions = $allTransactions->filter(function ($transaction) use ($validated) {

            if (
                !empty($validated['start_date']) &&
                $transaction->created_at->toDateString() < $validated['start_date']
            ) {
                return false;
            }

            if (
                !empty($validated['end_date']) &&
                $transaction->created_at->toDateString() > $validated['end_date']
            ) {
                return false;
            }

            return true;
        })->reverse()->values();

        return view('products.show', compact(
            'product',
            'transactions'
        ));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'supplier' => 'required|string|max:255',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
