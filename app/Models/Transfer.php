<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transfers';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_transfers';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_transfers',  
        'tabungan_asal', 
        'tabungan_tujuan', 
        'nominal', 
        'admin',
        'noted', 
        'created_by', 
        'modified_by'
    ];
}
