@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="mb-4">
        <h2>Inventory Dashboard</h2>

        <p class="text-muted">
            Overview of the current inventory.
        </p>
    </div>

    <!-- Statistics -->

    <div class="row g-4 mb-4">

        <div class="col-md-3">

            <div class="card bg-primary text-white shadow-sm h-100">

                <div class="card-body">

                    <h5>Total Products</h5>

                    <h2>
                        {{ $totalProducts }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card bg-warning shadow-sm h-100">

                <div class="card-body">

                    <h5>Low Stock</h5>

                    <h2>
                        {{ $lowStock }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card bg-danger text-white shadow-sm h-100">

                <div class="card-body">

                    <h5>Out of Stock</h5>

                    <h2>
                        {{ $outOfStock }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card bg-success text-white shadow-sm h-100">

                <div class="card-body">

                    <h5>Inventory Value</h5>

                    <h2>
                        ₱{{ number_format($inventoryValue, 2) }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    <!-- Recent Products -->

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="mb-0">
                    Recent Products
                </h4>

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

                                <td>
                                    {{ $product->name }}
                                </td>

                                <td>
                                    {{ $product->sku }}
                                </td>

                                <td>
                                    {{ $product->category }}
                                </td>

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

                                <td colspan="5"
                                    class="text-center">

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

