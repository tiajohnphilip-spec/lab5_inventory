@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="mb-4">
        <h2>Product Details</h2>
        <p class="text-muted">View the details of this product.</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <h4>{{ $product->name }}</h4>

            <hr>

            <p>
                <strong>SKU:</strong>
                {{ $product->sku }}
            </p>

            <p>
                <strong>Description:</strong>
                {{ $product->description ?? 'No description' }}
            </p>

            <p>
                <strong>Category:</strong>
                {{ $product->category }}
            </p>

            <p>
                <strong>Quantity:</strong>
                {{ $product->quantity }}
            </p>

            <p>
                <strong>Reorder Level:</strong>
                {{ $product->reorder_level }}
            </p>

            <p>
                <strong>Unit Price:</strong>
                ₱{{ number_format($product->unit_price, 2) }}
            </p>

            <p>
                <strong>Supplier:</strong>
                {{ $product->supplier }}
            </p>

            <a href="{{ route('products.edit', $product) }}"
               class="btn btn-warning">
                Edit
            </a>

            <a href="{{ route('products.index') }}"
               class="btn btn-secondary">
                Back to Products
            </a>

        </div>
    </div>

</div>

