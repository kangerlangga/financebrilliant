<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'programs';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_programs';

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
        'id_programs', 
        'code_programs', 
        'category_programs', 
        'title_programs', 
        'subtitle_programs', 
        'price_programs', 
        'admin_programs', 
        'benefit_programs', 
        'visib_programs', 
        'created_by', 
        'modified_by'
    ];
}
