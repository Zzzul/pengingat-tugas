<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Matkul extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sks', 'semester_id', 'user_id'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
