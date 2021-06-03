<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_transaksi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user', 'waktu_transaksi', 'jenis_transaksi', 'nominal_transaksi', 'transaksi_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
