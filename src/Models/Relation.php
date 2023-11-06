<?php

namespace Hani221b\Grace\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use HasFactory;

    protected $table = 'relations';

    protected $fillable = ['local_table', 'foreign_table'];
}
