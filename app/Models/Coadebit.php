<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coadebit extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'kode',
        'laporan',
    ];

    public function frontoffices()
    {
        return $this->hasMany(Frontoffice::class);
    }
    public function kasbesars()
    {
        return $this->hasMany(Frontoffice::class);
    }
    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
    

}
