
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Stock Adjustment
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Inventory Stock Adjustment
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('adjustments.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="product_id" class="form-label">
                                Select Product
                            </label>

                            <select name="product_id" id="product_id"
                                class="form-select" required>
                                <option value="">-- Select Product --</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="current_quantity" class="form-label">
                                Current System Quantity
                            </label>

                            <input type="number" id="current_quantity"
                                class="form-control" value="" readonly
                                placeholder="Select a product first">
                        </div>

                        <div class="mb-3">
                            <label for="physical_count" class="form-label">
                                Physical Count
                            </label>

                            <input type="number" name="physical_count"
                                id="physical_count" class="form-control"
                                min="0" required
                                value="{{ old('physical_count') }}"
                                placeholder="Enter actual counted quantity">
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">
                                Reason for Adjustment
                            </label>

                            <select name="reason" id="reason"
                                class="form-select" required>
                                <option value="">-- Select Reason --</option>
                                <option value="physical_count_discrepancy">
                                    Physical Count Discrepancy
                                </option>
                                <option value="damaged_goods">
                                    Damaged Goods
                                </option>
                                <option value="theft_loss">
                                    Theft or Loss
                                </option>
                                <option value="system_error">
                                    System Error
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                Additional Notes
                            </label>

                            <textarea name="notes" id="notes"
                                class="form-control" rows="3"
                                placeholder="Enter additional details (optional)">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Save Adjustment
                        </button>

                        <a href="{{ route('transactions.index') }}"
                            class="btn btn-secondary">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('product_id').addEventListener('change', function () {
            const productId = this.value;
            const quantityField = document.getElementById('current_quantity');

            quantityField.value = '';

            if (!productId) {
                quantityField.placeholder = 'Select a product first';
                return;
            }

            quantityField.placeholder = 'Loading...';

            fetch(`/adjustments/product/${productId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Unable to load product quantity.');
                    }

                    return response.json();
                })
                .then(data => {
                    quantityField.value = data.quantity;
                })
                .catch(error => {
                    quantityField.placeholder = 'Error loading quantity';
                    console.error(error);
                });
        });
    </script>
</x-app-layout>
