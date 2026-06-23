<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Monev extends Model
{
    use HasFactory;
    protected $table = 'monevs';
    protected $fillable = [
        'id_pengguna',
        'id_renja',
        'id_subprogram',
        'id_opd',


        'anggaran',
        'sumberdana',
        'dokumen_anggaran',
        'realisasi',
        'volumeTarget',
        'satuan_realisasi',
        'pesan',
        'status',
        'uraian',
        'is_locked',
		'edit_open_until',
    ];

    protected $casts = [
        'dokumen_anggaran' => 'array',
        'realisasi'        => 'array',
        'volumeTarget'     => 'array',
        'satuan_realisasi'     => 'array',
        'uraian'     => 'array',
		'is_locked' => 'boolean',
        'edit_open_until' => 'datetime',

    ];
    protected function dokumenAnggaran(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true) ?? [],
        );
    }


    public function penggunas()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
    public function subprogram()
    {
        return $this->belongsTo(Subprogram::class, 'id_subprogram', 'id');
    }

    public function fotoProgres()
    {
        return $this->hasMany(FotoProgres::class, 'id_monev', 'id');
    }
    public function Progres()
    {
        return $this->hasMany(FotoProgres::class, 'id_monev', 'id');
    }
    // public function rencanakerja()
    // {
    //     return $this->belongsTo(RencanaKerja::class, 'rencana_aksi', 'id');
    // }
    public function rencanakerja()
    {
        return $this->belongsTo(RencanaKerja::class, 'id_renja', 'id');
    }
    public function opd()
    {
        return $this->belongsTo(Opd::class, 'id_opd', 'id');
    }
    public function rencanaAksi()
    {
        // Pastikan nama model 'RencanaAksi' dan nama kolom 'rencana_aksi' sudah benar
        // Sesuaikan jika perlu
        return $this->belongsTo(RencanaAksi_6_tahun::class, 'rencana_aksi');
    }
    public function map()
    {
        return $this->hasOne(Map::class, 'id_monev', 'id');
    }
    // Ganti 'progresKerja' jika nama method relasi Anda berbeda
    public function progresKerja()
    {
        // Asumsi satu Monev bisa memiliki BANYAK progres kerja
        return $this->hasMany(ProgresKerja::class, 'id_monev', 'id');
    }
}
