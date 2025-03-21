<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'finances';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_finances';

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
        'id_finances',  
        'tabungan', 
        'saldo_awal', 
        'out_debit', 
        'in_kredit', 
        'saldo_akhir', 
        'noted', 
        'created_by', 
        'modified_by'
    ];
}
