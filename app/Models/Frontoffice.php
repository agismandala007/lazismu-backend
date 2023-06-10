<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frontoffice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'penyetor',
        'penerima',
        'nobuktipenerima',
        'tanggal',
        'ref',
        'jumlah',
        'tempatbayar',
        'coadebit_id',
        'coakredit_id',
        'cabang_id',
        
    ];

    public function coadebit()
    {
        return $this->belongsTo(Coa::class,'coadebit_id');
    }
    public function coakredit()
    {
        return $this->belongsTo(Coa::class,'coakredit_id');
    }
    public function cabangs()
    {
        return $this->belongsTo(Cabang::class);
    }
}
