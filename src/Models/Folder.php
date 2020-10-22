<?php

namespace Bigmom\VeEditor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'folder_type', 'created_at', 'updated_at'];

    private const FOLDER_TYPE = [
        'Static Asset',
        'Content Asset',
    ];

    public function assetTemplates()
    {
        return $this->hasMany(AssetTemplate::class);
    }

    public function hotspots()
    {
        return $this->belongsToMany(Hotspot::class);
    }

    public function getTypeNameAttribute()
    {
        return SELF::FOLDER_TYPE[$this->folder_type];
    }

    public function getCopyableAttribute()
    {
        $is_thumbnail = stripos($this->name, 'thumbnail') !== false;
        $is_inactive = stripos($this->name, 'inactive') !== false;
        return $this->assetTemplates->map(function($asset) use ($is_thumbnail, $is_inactive) {
            if ($is_inactive) {
                return "Inactive{$asset->typeName}:{$asset->url}";
            }
            else if ($is_thumbnail) {
                return "Thumbnail{$asset->typeName}:{$asset->url}";
            }
            return "{$asset->typeName}:{$asset->url}";
        })->implode(PHP_EOL);
    }
}
