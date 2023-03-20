<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kasbesar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'penerima',
        'nobuktikas',
        'tanggal',
        'ref',
        'jumlah',
        'coadebit_id',
        'coakredit_id',
        'cabang_id',
        'jenis_data',
    ];

    public function coadebit()
    {
        return $this->belongsTo(Coadebit::class);
    }
    public function coakredit()
    {
        return $this->belongsTo(Coakredit::class);
    }
}
