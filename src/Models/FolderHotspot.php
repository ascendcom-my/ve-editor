<?php

namespace Bigmom\VeEditor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolderHotspot extends Model
{
    use HasFactory;

    protected $table = 'folder_hotspot';
    protected $fillable = ['id', 'folder_id', 'hotspot_id', 'created_at', 'updated_at'];
}
