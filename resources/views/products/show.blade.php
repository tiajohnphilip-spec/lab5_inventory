
@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="mb-4">
        <h2>Product Details</h2>
        <p class="text-muted">View product information and transaction history.</p>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h4>{{ $product->name }}</h4>
            <hr>

            <p><strong>SKU:</strong> {{ $product->sku }}</p>
            <p><strong>Description:</strong> {{ $product->description ?? 'No description' }}</p>
            <p><strong>Category:</strong> {{ $product->category }}</p>
            <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
            <p><strong>Reorder Level:</strong> {{ $product->reorder_level }}</p>
            <p><strong>Unit Price:</strong> ₱{{ number_format($product->unit_price, 2) }}</p>
            <p><strong>Supplier:</strong> {{ $product->supplier }}</p>

            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                Edit
            </a>

            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                Back to Products
            </a>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Product Transaction History</h5>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('products.show', $product) }}"
                  class="row g-3 mb-4">

                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date"
                           class="form-control"
                           value="{{ request('start_date') }}">
                </div>

                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date"
                           class="form-control"
                           value="{{ request('end_date') }}">
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        Filter
                    </button>

                    <a href="{{ route('products.show', $product) }}"
                       class="btn btn-secondary">
                        Clear
                    </a>
                </div>

            </form>

            @if ($transactions->count() > 0)

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">

                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Running Balance</th>
                                <th>Reference Number</th>
                                <th>User</th>
                                <th>Notes</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>
                                        {{ $transaction->created_at->format('M d, Y h:i A') }}
                                    </td>

                                    <td>
                                        @if ($transaction->type === 'stock_in')
                                            <span class="badge bg-success">Stock In</span>
                                        @elseif ($transaction->type === 'stock_out')
                                            <span class="badge bg-danger">Stock Out</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Adjustment</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $transaction->quantity > 0 ? '+' : '' }}{{ $transaction->quantity }}
                                    </td>

                                    <td>
                                        <strong>{{ $transaction->running_balance }}</strong>
                                    </td>

                                    <td>
                                        {{ $transaction->reference_number ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $transaction->user->name ?? 'Unknown' }}
                                    </td>

                                    <td>
                                        {{ $transaction->notes ?? 'No notes' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            @else
                <p class="text-muted">
                    No transactions found for this date range.
                </p>
            @endif

        </div>
    </div>

</div>

@endsection
