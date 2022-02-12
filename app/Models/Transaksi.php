<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pembeli',
        'uang',
        "total_bayar",
        "kembalian",
        'kasir_id',
        'is_paid'
    ];

    /**
     * Get the Transaksi menu.
     */
    public function menu()
    {
        return $this->hasMany(TransaksiDetail::class, "transaksi_id", "id");
    }

    /**
     * Get the Kasir that created the Transaksi.
     */
    public function kasir()
    {
        return $this->hasOne(User::class, "id", "kasir_id");
    }
}
