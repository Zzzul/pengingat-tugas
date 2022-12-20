<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = ['semester_ke', 'user_id'];

    public function matkuls()
    {
        return $this->hasMany(Matkul::class);
    }

    public function scopeByLoggedInUser($query)
    {
        $query->where('user_id', auth()->id());
    }

    public function scopeByLoggedInUserAndId($query, $id)
    {
        $query->where(['id' => $id, 'user_id' => auth()->id()]);
    }

    public function scopeActiveSemester($query)
    {
        return $query->byLoggedInUser()
            ->select('id', 'semester_ke')
            ->where('aktif_smt', 1);
    }
}
