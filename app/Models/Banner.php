<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $table = 'banners';
    protected $fillable = ['id_pengguna', 'judul', 'status', 'file'];

    public function penggunas()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
}
