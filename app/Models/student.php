<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the books issued to the student.
     */
    public function issued_books()
    {
        return $this->hasMany(book_issue::class, 'student_id', 'id');
    }
}
