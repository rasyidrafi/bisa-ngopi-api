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
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'kasir_id',
    ];

    /**
     * Get the Transaksi Detail.
     */
    public function detail()
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
