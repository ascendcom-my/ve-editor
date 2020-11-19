<?php

namespace Bigmom\VeEditor\Models;

use Bigmom\VeEditor\Traits\CheckSize;
use Bigmom\VeEditor\Models\AssetTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Storage;

class Asset extends Model
{
    use CheckSize, HasFactory;

    protected $fillable = ['id', 'asset_template_id', 'dummy', 'path', 'created_at', 'updated_at'];

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
        if (!$this->checkSizeLimit($file)) {
            abort(500, 'Size limit exceeded');
        }
        config(config('ve.config'));
        $options = ['disk' => config('ve.storage')];
        if (AssetTemplate::find($this->asset_template_id)->folder->folder_type === 2) {
            $options['ContentDisposition'] = 'attachment';
        }
        $this->path = $file->storePublicly('assets', $options);

        $this->updateSize($file);

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

    public function updateSize($file)
    {
        $this->size = $file->getSize();
        Cache::put('occupied-size', $this->getOccupiedSize() + $this->size);
        return true;
    }
}
