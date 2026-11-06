<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'quantity' => $this->product->quantity,
            'reorder_level' => $this->product->reorder_level,
            'message' => $this->product->name . ' is running low on stock.',
        ];
    }
}
