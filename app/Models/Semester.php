<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = ['semester_ke', 'user_id'];

    public function matkuls()
    {
        return $this->hasMany(Matkul::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
