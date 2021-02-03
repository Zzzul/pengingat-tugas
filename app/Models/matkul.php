<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sks', 'semester_id'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }


    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
}
