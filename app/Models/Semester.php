<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = ['semester_ke'];

    public function matkuls()
    {
        return $this->hasMany(Matkul::class);
    }
}
