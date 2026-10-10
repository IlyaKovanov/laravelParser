<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'base_url',
        'description',
        'user_id'
    ];

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function selectors()
    {
        return $this->hasMany(Selector::class);
    }
}
