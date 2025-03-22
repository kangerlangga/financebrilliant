<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'periodes';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_periodes';

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
        'id_periodes', 
        'date_periodes', 
        'status_periodes',
        'category_periodes',
        'created_by', 
        'modified_by'
    ];
}
