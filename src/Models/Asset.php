<?php

namespace Bigmom\VeEditor\Models;

use Bigmom\VeEditor\Facades\Asset as AssetManager;
use Bigmom\VeEditor\Models\AssetTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Storage;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'asset_template_id', 'dummy', 'path', 'size', 'created_at', 'updated_at'];

    public function assetTemplate()
    {
        return $this->belongsTo(AssetTemplate::class);
    }

    public function getUrlAttribute()
    {
        return Storage::disk(config('ve.storage'))->url($this->path);
    }

    public function store($file)
    {
        if (AssetManager::checkSizeLimit($file) === false) {
            return false;
        }

        config(config('ve.config'));
        $options = ['disk' => config('ve.storage')];
        if (AssetTemplate::find($this->asset_template_id)->folder->folder_type === 2) {
            $options['ContentDisposition'] = 'attachment; filename="' . $file->getClientOriginalName() . '"';
        }

        $this->path = $file->storePublicly('assets', $options);

        $this->addSize($file);

        return $this->path;
    }

    public function storeByKey($key)
    {
        if (AssetManager::checkSizeLimit(Storage::disk('s3')->getSize($key)) === false) {
            return false;
        }

        $newKey = str_replace('tmp/', 'assets/', $key);

        Storage::disk('s3')->copy($key, $newKey);

        $this->path = $newKey;
        
        $this->addSize(Storage::disk('s3')->size($newKey));

        return $this->path;
    }

    public function deleteAsset($disk = null)
    {
        if (!$disk) {
            $disk = config('ve.storage');
        }

        $this->reduceSize(Storage::disk($disk)->size($this->path));

        Storage::disk($disk)->delete($this->path);

        return $this;
    }

    public function addSize($file)
    {
        if (is_int($file)) {
            $this->size = $file;
        } else {
            $this->size = $file->getSize();
        }
        Cache::put('occupied-size', AssetManager::getOccupiedSize() + $this->size);
        return true;
    }

    public function reduceSize($size)
    {
        Cache::put('occupied-size', AssetManager::getOccupiedSize() - $size);
    }
}
