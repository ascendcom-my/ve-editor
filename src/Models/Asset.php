<?php

namespace Bigmom\VeEditor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Asset extends Model
{
    use HasFactory;

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
        config(config('ve.config'));
        $options = ['disk' => config('ve.storage')];
        if ($file->asset_template->folder->folder_type === 2) {
            $options['ContentDisposition'] = 'attachment';
        }
        $this->path = $file->storePublicly('assets', $options);

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
}
