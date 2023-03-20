<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coakredit extends Model
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
        'cabang_id'
    ];

    public function frontoffices()
    {
        return $this->hasMany(Frontoffice::class);
    }
}
