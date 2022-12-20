<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = ['matkul_id', 'deskripsi', 'batas_wakut', 'selesai', 'pertemuan_ke'];

    public static function boot(): void
    {
        static::creating(fn (Model $model) =>
            $model->user_id = auth()->id(),
        );
    }

    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }
}
