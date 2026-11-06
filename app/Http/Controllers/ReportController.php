<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Stock Movement Report
        $transactions = InventoryTransaction::with(['product', 'user'])
            ->when($validated['start_date'] ?? null, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($validated['end_date'] ?? null, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->latest()
            ->get();

        // Low Stock Report
        $lowStockProducts = Product::lowStock()
            ->orderBy('name')
            ->get();

        // Inventory Valuation Report
        $products = Product::orderBy('name')->get();

        $totalValuation = $products->sum(function ($product) {
            return $product->quantity * $product->unit_price;
        });

        return view('reports.index', compact(
            'transactions',
            'lowStockProducts',
            'products',
            'totalValuation'
        ));
    }
}
