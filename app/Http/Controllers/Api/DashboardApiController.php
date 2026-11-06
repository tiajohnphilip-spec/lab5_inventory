<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\JsonResponse;

class DashboardApiController extends Controller
{
    // Dashboard Statistics
    public function stats(): JsonResponse
    {
        return response()->json([
            'total_products' => Product::count(),
            'low_stock' => Product::lowStock()->count(),
            'out_of_stock' => Product::outOfStock()->count(),
            'total_transactions' => InventoryTransaction::count(),
        ]);
    }

    // Low Stock Products
    public function lowStock(): JsonResponse
    {
        $products = Product::lowStock()->get();

        return response()->json($products);
    }

    // Recent Transactions
    public function recentTransactions(): JsonResponse
    {
        $transactions = InventoryTransaction::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json($transactions);
    }
}
