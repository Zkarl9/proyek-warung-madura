<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PermintaanDeteksiProduk extends Notification
{
    use Queueable;

    public function __construct(public Product $product)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'nama_produk' => $this->product->nama_produk,
            'yolo_label' => $this->product->yolo_label,
            'pesan' => "Owner meminta produk \"{$this->product->nama_produk}\" didaftarkan ke deteksi AI.",
        ];
    }
}
