<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'yolo_label',
        'kategori',
        'stok_pajangan',
        'stok_minimum',
        'harga',
        'satuan',
        'foto',
        'status_ai',
        'diminta_deteksi_at',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok_pajangan' => 'integer',
        'stok_minimum' => 'integer',
        'diminta_deteksi_at' => 'datetime',
    ];

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    public function isStokTipis(): bool
    {
        return $this->stok_pajangan <= $this->stok_minimum;
    }

    public function scopeStokTipis($query)
    {
        return $query->whereColumn('stok_pajangan', '<=', 'stok_minimum');
    }
}