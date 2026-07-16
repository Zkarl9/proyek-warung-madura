<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class StockMovement extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'product_id',
        'user_id',
        'arah',
        'jumlah',
        'harga_beli',
        'sumber',
        'alasan',
        'sumber_catatan',
        'keterangan',
    ];
 
    protected $casts = [
        'jumlah' => 'integer',
        'harga_beli' => 'integer',
    ];
 
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
 
    public function scopeMasuk($query)
    {
        return $query->where('arah', 'masuk');
    }
 
    public function scopeKeluar($query)
    {
        return $query->where('arah', 'keluar');
    }
 
    // Alias biar kode/view lama yang masih mengacu ke ->tipe (dari eks-tabel stock_outs) tetap jalan
    // tanpa perlu diubah satu-satu.
    public function getTipeAttribute()
    {
        return $this->sumber_catatan;
    }
}
