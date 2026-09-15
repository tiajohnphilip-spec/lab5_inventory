@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="mb-4">
        <h2>Add Product</h2>
        <p class="text-muted">Add a new product to the inventory.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('products.store') }}" method="POST">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">SKU</label>
                    <input type="text"
                           name="sku"
                           class="form-control"
                           value="{{ old('sku') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text"
                           name="category"
                           class="form-control"
                           value="{{ old('category') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number"
                           name="quantity"
                           class="form-control"
                           value="{{ old('quantity', 0) }}"
                           min="0"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reorder Level</label>
                    <input type="number"
                           name="reorder_level"
                           class="form-control"
                           value="{{ old('reorder_level', 5) }}"
                           min="0"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit Price</label>
                    <input type="number"
                           name="unit_price"
                           class="form-control"
                           value="{{ old('unit_price') }}"
                           min="0"
                           step="0.01"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Supplier</label>
                    <input type="text"
                           name="supplier"
                           class="form-control"
                           value="{{ old('supplier') }}"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Product
                </button>

                <a href="{{ route('products.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

@endsection