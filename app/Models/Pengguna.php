<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // PENTING
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'penggunas';
    protected $fillable = ['id_opd','nama', 'username', 'password', 'level'];

    protected $hidden = ['password', 'remember_token'];



    public function subprogram()
    {
        return $this->hasMany(SubProgram::class, 'id_pengguna', 'id');
    }
    public function foto_subprograms()
    {
        return $this->hasMany(FotoSubprogram::class, 'id_pengguna', 'id');
    }
    public function rencana_aksi()
    {
        return $this->hasMany(RencanaAksi_6_tahun::class, 'id_pengguna', 'id');
    }
    public function rencana_kerja()
    {
        return $this->hasMany(RencanaKerja::class, 'id_pengguna', 'id');
    }

    public function progres_kerja()
    {
        return $this->hasMany(ProgresKerja::class, 'id_pengguna', 'id');
    }
    public function monev()
    {
        return $this->hasMany(Monev::class, 'id_pengguna', 'id');
    }

    public function kontaks()
    {
        return $this->hasMany(Kontak::class, 'id_pengguna', 'id');
    }
    public function opd()
    {
        return $this->belongsTo(Opd::class, 'id_opd', 'id');
    }

    public function maps()
    {
        return $this->hasMany(Map::class, 'id_pengguna', 'id');
    }
    public function foto_progres()
    {
        return $this->hasMany(FotoProgres::class, 'id_pengguna', 'id');
    }
    public function profil()
    {
        return $this->hasMany(Profil::class, 'id_pengguna', 'id');
    }
}
