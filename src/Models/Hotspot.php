<?php

namespace Bigmom\VeEditor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotspot extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'scene_id', 'position', 'name', 'meta', 'medias', 'created_at', 'updated_at'];

    public function scene()
    {
        return $this->belongsTo(Scene::class);
    }

    public function folders()
    {
        return $this->belongsToMany(Folder::class);
    }
}
