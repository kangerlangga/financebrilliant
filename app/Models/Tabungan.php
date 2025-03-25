<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tabungans';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_tabungans';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_tabungans',
        'category_tabungans', 
        'nama_tabungans',
        'rekening_tabungans',
        'logo_tabungans',
        'status_tabungans',
        'created_by', 
        'modified_by'
    ];
}
