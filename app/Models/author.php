<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class author extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function books()
    {
        return $this->hasMany(book::class, 'author_id');
    }
}
