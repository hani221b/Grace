<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    //=========================================
    // Table's name in database
    //=========================================

    protected $table = 'tables';

    //=========================================
    // Fillable values
    //=========================================

    protected $fillable = [
        'table_name', 'controller', 'model', 'request', 'resource', 'migration', 'views'
    ];
}
