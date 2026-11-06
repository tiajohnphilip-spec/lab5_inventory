
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inventory Reports
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Date Filter --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    Report Date Filter
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('reports.index') }}"
                          class="row g-3">

                        <div class="col-md-4">
                            <label for="start_date" class="form-label">
                                Start Date
                            </label>
                            <input type="date" name="start_date"
                                   id="start_date" class="form-control"
                                   value="{{ request('start_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label for="end_date" class="form-label">
                                End Date
                            </label>
                            <input type="date" name="end_date"
                                   id="end_date" class="form-control"
                                   value="{{ request('end_date') }}">
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                Filter Reports
                            </button>

                            <a href="{{ route('reports.index') }}"
                               class="btn btn-secondary">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Stock Movement Report --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Stock Movement Report</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Reference</th>
                                    <th>User</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>
                                            {{ $transaction->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td>
                                            {{ $transaction->product->name ?? 'Deleted Product' }}
                                        </td>
                                        <td>
                                            {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                        </td>
                                        <td>
                                            {{ $transaction->quantity > 0 ? '+' : '' }}{{ $transaction->quantity }}
                                        </td>
                                        <td>
                                            {{ $transaction->reference_number ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $transaction->user->name ?? 'Unknown' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No stock movements found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Low Stock Report --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Low Stock Report</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Current Quantity</th>
                                    <th>Reorder Level</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($lowStockProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->category }}</td>
                                        <td>
                                            <span class="badge bg-danger">
                                                {{ $product->quantity }}
                                            </span>
                                        </td>
                                        <td>{{ $product->reorder_level }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No low stock products found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Inventory Valuation Report --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Inventory Valuation Report</h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>
                                            ₱{{ number_format($product->unit_price, 2) }}
                                        </td>
                                        <td>
                                            ₱{{ number_format($product->quantity * $product->unit_price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr class="table-success">
                                    <th colspan="4" class="text-end">
                                        Total Inventory Value:
                                    </th>
                                    <th>
                                        ₱{{ number_format($totalValuation, 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
