
@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="mb-4">
        <h2>Inventory Dashboard</h2>
        <p class="text-muted">
            Overview of the current inventory.
        </p>
        <small class="text-muted">
            Last Updated: <span id="lastUpdated">Not yet updated</span>
        </small>
    </div>

    <!-- Statistics -->

    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <h5>Total Products</h5>
                    <h2 id="totalProducts">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning shadow-sm h-100">
                <div class="card-body">
                    <h5>Low Stock</h5>
                    <h2 id="lowStock">{{ $lowStock }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm h-100">
                <div class="card-body">
                    <h5>Out of Stock</h5>
                    <h2 id="outOfStock">{{ $outOfStock }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <h5>Inventory Value</h5>
                    <h2>₱{{ number_format($inventoryValue, 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-dark shadow-sm h-100">
                <div class="card-body">
                    <h5>Total Transactions</h5>
                    <h2 id="totalTransactions">0</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Low Stock Alerts -->

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h4 class="mb-3">Low Stock Alerts</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                            <th>Reorder Level</th>
                        </tr>
                    </thead>

                    <tbody id="lowStockTable">
                        <tr>
                            <td colspan="4" class="text-center">
                                Loading low stock products...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Recent Transactions -->

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h4 class="mb-3">Recent Transactions</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Reference Number</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody id="recentTransactionsTable">
                        <tr>
                            <td colspan="6" class="text-center">
                                Loading recent transactions...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Recent Products -->

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Recent Products</h4>

                <a href="{{ route('products.index') }}"
                   class="btn btn-primary">
                    View All Products
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($recentProducts as $product)

                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category }}</td>

                                <td>
                                    @if ($product->quantity == 0)
                                        <span class="badge bg-danger">
                                            Out of Stock
                                        </span>
                                    @elseif ($product->quantity <= $product->reorder_level)
                                        <span class="badge bg-warning text-dark">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            {{ $product->quantity }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    ₱{{ number_format($product->unit_price, 2) }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    No products available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('products.index') }}"
           class="btn btn-primary">
            Manage Inventory
        </a>
    </div>

</div>

<style>
    .number-updated {
        animation: numberPulse 0.5s ease;
    }

    @keyframes numberPulse {
        50% {
            transform: scale(1.12);
            opacity: 0.7;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const statsUrl = '/api/dashboard/stats';
    const lowStockUrl = '/api/products/low-stock';
    const transactionsUrl = '/api/transactions/recent';

    function updateNumber(id, value) {
        const element = document.getElementById(id);

        if (element && element.textContent !== String(value)) {
            element.textContent = value;
            element.classList.remove('number-updated');
            void element.offsetWidth;
            element.classList.add('number-updated');
        }
    }

    function escapeHTML(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    async function updateDashboard() {

        try {
            const [statsResponse, lowStockResponse, transactionsResponse] =
                await Promise.all([
                    fetch(statsUrl),
                    fetch(lowStockUrl),
                    fetch(transactionsUrl)
                ]);

            if (!statsResponse.ok ||
                !lowStockResponse.ok ||
                !transactionsResponse.ok) {
                throw new Error('Unable to load dashboard data.');
            }

            const stats = await statsResponse.json();
            const lowStockProducts = await lowStockResponse.json();
            const transactions = await transactionsResponse.json();

            // Update Statistics
            updateNumber('totalProducts', stats.total_products);
            updateNumber('lowStock', stats.low_stock);
            updateNumber('outOfStock', stats.out_of_stock);
            updateNumber('totalTransactions', stats.total_transactions);

            // Update Low Stock Alerts
            const lowStockTable = document.getElementById('lowStockTable');

            if (lowStockProducts.length === 0) {
                lowStockTable.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center">
                            No low stock products.
                        </td>
                    </tr>
                `;
            } else {
                lowStockTable.innerHTML = lowStockProducts.map(product => `
                    <tr>
                        <td>${escapeHTML(product.name)}</td>
                        <td>${escapeHTML(product.sku)}</td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                ${escapeHTML(product.quantity)}
                            </span>
                        </td>
                        <td>${escapeHTML(product.reorder_level)}</td>
                    </tr>
                `).join('');
            }

            // Update Recent Transactions
            const transactionsTable =
                document.getElementById('recentTransactionsTable');

            if (transactions.length === 0) {
                transactionsTable.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">
                            No recent transactions.
                        </td>
                    </tr>
                `;
            } else {
                transactionsTable.innerHTML = transactions.map(transaction => `
                    <tr>
                        <td>${escapeHTML(transaction.product?.name ?? 'N/A')}</td>
                        <td>${escapeHTML(transaction.user?.name ?? 'N/A')}</td>
                        <td>${escapeHTML(transaction.type)}</td>
                        <td>${escapeHTML(transaction.quantity)}</td>
                        <td>${escapeHTML(transaction.reference_number ?? 'N/A')}</td>
                        <td>${transaction.created_at
                            ? new Date(transaction.created_at).toLocaleString()
                            : 'N/A'}</td>
                    </tr>
                `).join('');
            }

            // Update Timestamp
            document.getElementById('lastUpdated').textContent =
                new Date().toLocaleString();

        } catch (error) {
            console.error('Dashboard update failed:', error);
            document.getElementById('lastUpdated').textContent =
                'Update failed. Retrying...';
        }
    }

    // Initial update
    updateDashboard();

    // Automatic update every 30 seconds
    setInterval(updateDashboard, 30000);

});
</script>

@endsection
