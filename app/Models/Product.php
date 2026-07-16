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
        'status_kamera',
        'harga',
        'satuan',
        'foto',
        'status_ai',
        'diminta_deteksi_at',
    ];
 
    protected $casts = [
        'harga' => 'integer',
        'stok_pajangan' => 'integer',
        'diminta_deteksi_at' => 'datetime',
    ];
 
    public function stockIns(): HasMany
    {
        return $this->hasMany(StockMovement::class)->where('arah', 'masuk');
    }
 
    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockMovement::class)->where('arah', 'keluar');
    }
 
    // Status stok manual (dari input Kelola Produk), dipakai untuk dashboard & badge.
    public function isAda(): bool
    {
        return $this->stok_pajangan > 0;
    }
 
    // Status hasil deteksi kamera real-time, dipakai untuk trigger notifikasi Telegram.
    public function isTerdeteksiHabis(): bool
    {
        return $this->status_kamera === 'habis';
    }
 
    public function scopeTidakAda($query)
    {
        return $query->where('stok_pajangan', 0);
    }
 
    // Rata-rata tertimbang harga beli dari semua Stok Masuk yang pernah diisi harga belinya.
    // Ditimbang berdasarkan jumlah, bukan cuma dirata-rata biasa — jadi restock 100 pcs @Rp2.000
    // lebih berpengaruh ke rata-rata dibanding restock 5 pcs @Rp2.500.
    public function hargaBeliRataRata(): ?float
    {
        $data = $this->stockIns()
            ->whereNotNull('harga_beli')
            ->selectRaw('SUM(harga_beli * jumlah) as total_biaya, SUM(jumlah) as total_jumlah')
            ->first();
 
        if (! $data || (int) $data->total_jumlah === 0) {
            return null; // belum pernah ada Stok Masuk yang diisi harga belinya
        }
 
        return $data->total_biaya / $data->total_jumlah;
    }
}
