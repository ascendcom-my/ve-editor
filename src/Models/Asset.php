<?php

namespace Bigmom\VeEditor\Models;

use Bigmom\Traits\CheckSize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        if (!$this->checkSize($file)) {
            abort(500, 'Size limit exceeded');
        }
        config(config('ve.config'));
        $options = ['disk' => config('ve.storage')];
        if ($this->asset_template->folder->folder_type === 2) {
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
