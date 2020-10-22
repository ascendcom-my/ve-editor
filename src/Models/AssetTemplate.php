<?php

namespace Bigmom\VeEditor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class AssetTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'file_type', 'requirement', 'sequence', 'folder_id', 'created_at', 'updated_at'];

    private const ASSET_TYPE = [
        'Image',
        'Video',
        'PDF',       
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function getTypeNameAttribute()
    {
        return SELF::ASSET_TYPE[$this->file_type];
    }

    public function getUrlAttribute()
    {
        return count($this->assets) ? $this->assets()->latest()->first()->url : null;
    }
}
