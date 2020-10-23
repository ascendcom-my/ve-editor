<?php

namespace Bigmom\VeEditor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Scene extends Model
{
    use HasFactory;

    const TYPE = [
        'Scene 2D',
        '3D Model',
        'Scene 2D (Video)',
    ];

    protected $fillable = ['id', 'name', 'type', 'path', 'extras', 'created_at', 'updated_at'];

    public function hotspots()
    {
        return $this->hasMany(Hotspot::class);
    }

    public function placeholders()
    {
        return $this->hasMany(Placeholder::class);
    }

    public function getTypeNameAttribute()
    {
        return SELF::TYPE[$this->type];
    }

    public function getUrlAttribute()
    {
        return Storage::disk(config('ve.storage'))->url($this->path);
    }

    public function store($file)
    {
        if ($this->path) {
            $this->deleteAsset();
        }
        config(config('ve.config'));
        $this->path = $file->storePublicly('scene', config('ve.storage'));

        return $this->path;
    }

    public function deleteAsset($disk = null)
    {
        if (!$disk) {
            $disk = config('ve.storage');
        }

        Storage::disk($disk)->delete($this->path);

        return $this;
    }

    public function getBladeAttribute()
    {
        return 'veeditor::scene.' . [
            'image',
            'glb',
            'image',
        ][$this->type];
    }
}
