<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            Inventory Management System
        </a>

        <div class="text-white">
            {{ Auth::user()->name }}

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger ms-2">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Products</h2>

        <a href="{{ route('products.create') }}" class="btn btn-primary">
            + Add Product
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Reorder Level</th>
                    <th>Unit Price</th>
                    <th>Supplier</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($products as $product)

                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category }}</td>

                        <td>
                            @if ($product->quantity == 0)
                                <span class="badge bg-danger">Out of Stock</span>
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

                        <td>{{ $product->reorder_level }}</td>

                        <td>
                            ₱{{ number_format($product->unit_price, 2) }}
                        </td>

                        <td>{{ $product->supplier }}</td>

                        <td>
                            <a href="{{ route('products.show', $product) }}"
                               class="btn btn-sm btn-info">
                                View
                            </a>

                            <a href="{{ route('products.edit', $product) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('products.destroy', $product) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this product?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-danger">
                                    Delete
                                </button>

                            </form>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-center">
                            No products found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>

</body>
</html>