<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tag extends Model
{
    use HasFactory;
    protected $fillable = ['nama_tag','slug'];
    public function berita(){
        return $this->belongsToMany(berita::class, 'tag_berita','id_tag','id_berita');
    }
}
