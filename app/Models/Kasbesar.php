<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
