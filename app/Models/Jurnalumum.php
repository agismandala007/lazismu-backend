<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnalumum extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'name',
        'penerima',
        'nobukti',
        'tanggal',
        'ref',
        'jumlah',
        'coadebit_id',
        'coakredit_id',
        'cabang_id'
    ];

    public function coadebit()
    {
        return $this->belongsTo(Coa::class,'coadebit_id');
    }
    public function coakredit()
    {
        return $this->belongsTo(Coa::class,'coakredit_id');
    }
}
