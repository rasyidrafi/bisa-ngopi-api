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
    ];

    /**
     * Get the Transaksi detail.
     */
    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class, "transaksi_id", "id");
    }
}
