<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaction Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">
                    Inventory Transaction Information
                </h3>

                <table class="w-full border-collapse border">
                    <tr>
                        <th class="border p-3 text-left">Product</th>
                        <td class="border p-3">
                            {{ $transaction->product->name ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="border p-3 text-left">SKU</th>
                        <td class="border p-3">
                            {{ $transaction->product->sku ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="border p-3 text-left">User</th>
                        <td class="border p-3">
                            {{ $transaction->user->name ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="border p-3 text-left">Transaction Type</th>
                        <td class="border p-3">
                            {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="border p-3 text-left">Quantity</th>
                        <td class="border p-3">
                            {{ $transaction->quantity }}
                        </td>
                    </tr>

                    <tr>
                        <th class="border p-3 text-left">Reference Number</th>
                        <td class="border p-3">
                            {{ $transaction->reference_number ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="border p-3 text-left">Notes</th>
                        <td class="border p-3">
                            {{ $transaction->notes ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="border p-3 text-left">Date</th>
                        <td class="border p-3">
                            {{ $transaction->created_at->format('F d, Y h:i A') }}
                        </td>
                    </tr>
                </table>

                <div class="mt-4">
                    <a href="{{ route('transactions.index') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded">
                        Back to Transactions
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>