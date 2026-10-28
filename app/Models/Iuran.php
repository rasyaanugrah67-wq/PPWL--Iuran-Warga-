<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $table = 'iuran';
    protected $primaryKey = 'id_iuran';
    protected $fillable = ['id_warga', 'id_periode', 'nominal', 'jatuh_tempo', 'status'];
    protected $casts = [
        'jatuh_tempo' => 'date',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'id_warga');
    }
}