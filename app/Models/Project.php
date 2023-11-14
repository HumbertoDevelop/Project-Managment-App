<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    // Many to many relationships inverse
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
    // Many to many relationships inverse
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
