
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inventory Transactions
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 class="text-lg font-bold">
                        Transaction History
                    </h3>

                    <a href="{{ route('transactions.create') }}"
                       style="background-color: #2563eb; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; display: inline-block;">
                        + Add Transaction
                    </a>
                </div>

                <!-- Transaction Filters -->
                <form method="GET" action="{{ route('transactions.index') }}"
                      style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">

                    <select name="product_id" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="">All Products</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="type" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="">All Types</option>
                        <option value="stock_in" {{ request('type') == 'stock_in' ? 'selected' : '' }}>
                            Stock In
                        </option>
                        <option value="stock_out" {{ request('type') == 'stock_out' ? 'selected' : '' }}>
                            Stock Out
                        </option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>
                            Adjustment
                        </option>
                    </select>

                    <input type="date"
                           name="date"
                           value="{{ request('date') }}"
                           style="padding: 8px; border: 1px solid #ccc; border-radius: 5px;">

                    <button type="submit"
                            style="background: #2563eb; color: white; padding: 8px 16px; border: none; border-radius: 5px;">
                        Filter
                    </button>

                    <a href="{{ route('transactions.index') }}"
                       style="background: #6b7280; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none;">
                        Reset
                    </a>
                </form>

                <!-- Transactions Table -->
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f3f4f6;">
                                <th style="border: 1px solid #ddd; padding: 12px;">Product</th>
                                <th style="border: 1px solid #ddd; padding: 12px;">User</th>
                                <th style="border: 1px solid #ddd; padding: 12px;">Type</th>
                                <th style="border: 1px solid #ddd; padding: 12px;">Quantity</th>
                                <th style="border: 1px solid #ddd; padding: 12px;">Date</th>
                                <th style="border: 1px solid #ddd; padding: 12px;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 12px;">
                                        {{ $transaction->product->name ?? 'N/A' }}
                                    </td>

                                    <td style="border: 1px solid #ddd; padding: 12px;">
                                        {{ $transaction->user->name ?? 'N/A' }}
                                    </td>

                                    <td style="border: 1px solid #ddd; padding: 12px;">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                    </td>

                                    <td style="border: 1px solid #ddd; padding: 12px;">
                                        {{ $transaction->quantity }}
                                    </td>

                                    <td style="border: 1px solid #ddd; padding: 12px;">
                                        {{ $transaction->created_at->format('M d, Y h:i A') }}
                                    </td>

                                    <td style="border: 1px solid #ddd; padding: 12px; text-align: center; white-space: nowrap;">

                                        <a href="{{ route('transactions.show', $transaction->id) }}"
                                           style="background-color: #2563eb; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; display: inline-block;">
                                            VIEW
                                        </a>

                                        @if (auth()->user()->role === 'admin')
                                            <form method="POST"
                                                  action="{{ route('transactions.destroy', $transaction->id) }}"
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to delete this transaction? Stock will also be reversed.');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        style="background-color: #dc2626; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer;">
                                                    DELETE
                                                </button>
                                            </form>
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="border: 1px solid #ddd; padding: 20px; text-align: center;">
                                        No transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 20px;">
                    {{ $transactions->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
