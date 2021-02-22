<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = ['matkul_id', 'deskripsi', 'batas_wakut', 'selesai', 'pertemuan_ke', 'user_id'];

    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
