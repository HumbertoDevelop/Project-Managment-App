<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description','due_date','completed'];

    // Many to many relationships
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
