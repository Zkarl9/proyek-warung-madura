<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_outs';

    protected $fillable = [
        'product_id',
        'jumlah',
        'tipe',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}