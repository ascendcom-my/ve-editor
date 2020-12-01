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

    public function assetTemplate()
    {
        return $this->belongsTo(AssetTemplate::class);
    }

    public function getTypeNameAttribute()
    {
        return SELF::TYPE[$this->type];
    }

    public function getUrlAttribute()
    {
        return $this->assetTemplate->url;
    }

    public function store($file)
    {
        if (!$this->assetTemplate) {
            $assetTemplate = new AssetTemplate;
            $assetTemplate->name = $this->name;
            switch ($this->type) {
                case 0: $assetTemplate->file_type = 0;
                break;
                case 2: $assetTemplate->file_type = 1;
                break;
                default: $assetTemplate->file_type = 0;
            }
            $assetTemplate->requirement = '';
            $assetTemplate->folder_id = Folder::where('name', 'Scenes')->first()->id;
            $assetTemplate->sequence = AssetTemplate::where('folder_id', $assetTemplate->folder_id)->count();
            $assetTemplate->save();
            $this->asset_template_id = $assetTemplate->id;
        }

        $asset = new Asset;

        $asset->asset_template_id = $this->asset_template_id;
        $this->path = $asset->store($file);

        if ($this->path === false) {
            return false;
        }

        $asset->save();

        return $this->path;
    }

    public function storeByKey($key)
    {
        if (!$this->assetTemplate) {
            $assetTemplate = new AssetTemplate;
            $assetTemplate->name = $this->name;
            switch ($this->type) {
                case 0: $assetTemplate->file_type = 0;
                break;
                case 2: $assetTemplate->file_type = 1;
                break;
                default: $assetTemplate->file_type = 0;
            }
            $assetTemplate->requirement = '';
            $assetTemplate->folder_id = Folder::where('name', 'Scenes')->first()->id;
            $assetTemplate->sequence = AssetTemplate::where('folder_id', $assetTemplate->folder_id)->count();
            $assetTemplate->save();
            $this->asset_template_id = $assetTemplate->id;
        }

        $asset = new Asset;

        $asset->asset_template_id = $this->asset_template_id;
        $this->path = $asset->storeByKey($key);

        if ($this->path === false) {
            return false;
        }

        $asset->save();

        return $this->path;
    }

    public function deleteAssetTemplate($disk = null)
    {
        if (!$disk) {
            $disk = config('ve.storage');
        }
        
        if ($this->assetTemplate) {
            foreach ($this->assetTemplate->assets as $asset) {
                $asset->deleteAsset()->delete();
            }

            $this->assetTemplate->delete();
        }

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
