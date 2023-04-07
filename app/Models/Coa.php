<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kode',
        'laporan',
        'cabang_id',
        'tipe'
    ];

    public function frontoffices()
    {
        return $this->hasMany(Frontoffice::class);
    }
    public function kasbesars()
    {
        return $this->hasMany(Kasbesar::class);
    }
    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
