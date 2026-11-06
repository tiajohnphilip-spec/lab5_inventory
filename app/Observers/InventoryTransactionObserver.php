<?php
namespace App\Observers;

use App\Models\InventoryTransaction;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryTransactionObserver
{
    public function created(InventoryTransaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $product = $transaction->product()
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (int) $transaction->quantity;

            if ($transaction->type === 'stock_in') {
                $newQuantity = $product->quantity + $quantity;
            } elseif ($transaction->type === 'stock_out') {
                $newQuantity = $product->quantity - $quantity;
            } elseif ($transaction->type === 'adjustment') {
                $newQuantity = $product->quantity + $quantity;
            } else {
                throw ValidationException::withMessages([
                    'type' => 'Invalid transaction type.',
                ]);
            }

            if ($newQuantity < 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'Insufficient stock available.',
                ]);
            }

            $product->quantity = $newQuantity;
            $product->save();

            if ($product->quantity <= $product->reorder_level) {
                $admins = User::whereIn('role', ['admin', 'manager'])->get();

                foreach ($admins as $admin) {
                    $admin->notify(new LowStockNotification($product));
                }
            }
        });
    }
}
