<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaksi_id',
        'menu_id',
        'jumlah'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */

    protected $hidden = [
        'transaksi_id',
        'menu_id',
        'created_at',
        'updated_at'
    ];

     /**
     * Get the Transaksi Menu.
     */
    public function menu()
    {
        return $this->hasOne(Menu::class, "id", "menu_id");
    }
}
