<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];


    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function coadebits()
    {
        return $this->hasMany(Coa::class);
    }

    public function coakredits()
    {
        return $this->hasMany(Coa::class);
    }

    public function frontoffices()
    {
        return $this->hasMany(Frontoffice::class);
    }

    public function jurnalumums()
    {
        return $this->hasMany(Jurnalumum::class);
    }

    public function kasbanks()
    {
        return $this->hasMany(Kasbank::class);
    }

    public function kasbesars()
    {
        return $this->hasMany(Kasbesar::class);
    }

    public function kaskecils()
    {
        return $this->hasMany(Kaskecil::class);
    }

}
