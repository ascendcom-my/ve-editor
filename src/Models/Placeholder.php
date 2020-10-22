<?php

namespace Bigmom\VeEditor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placeholder extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'position', 'url', 'scene_id', 'created_at', 'updated_at'];
}
