<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_email';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_pengirim', 'id_penerima', 'email_penerima', 'subjek', 'konten', 'email_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
