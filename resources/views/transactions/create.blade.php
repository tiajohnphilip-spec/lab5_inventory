<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Inventory Transaction
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Product</label>
                        <select name="product_id" class="w-full border-gray-300 rounded-md" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (Stock: {{ $product->quantity }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Transaction Type</label>
                        <select name="type" class="w-full border-gray-300 rounded-md" required>
                            <option value="">Select Type</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}"
                            class="w-full border-gray-300 rounded-md" required>
                        @error('quantity')
                            <p class="text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Reference Number</label>
                        <input type="text" name="reference_number"
                            value="{{ old('reference_number') }}"
                            class="w-full border-gray-300 rounded-md">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Notes</label>
                        <textarea name="notes" rows="3"
                            class="w-full border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md">
                            Save Transaction
                        </button>

                        <a href="{{ route('transactions.index') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded-md">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>