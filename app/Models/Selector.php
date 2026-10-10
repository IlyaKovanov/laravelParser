<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Selector extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'selector',
        'selector_type_id',
        'project_id'
    ];
}
