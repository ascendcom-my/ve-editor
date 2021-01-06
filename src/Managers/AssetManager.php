<?php

namespace Bigmom\VeEditor\Managers;

use Bigmom\VeEditor\Facades\Scene as SceneManager;
use Bigmom\VeEditor\Models\Asset;
use Bigmom\VeEditor\Models\AssetTemplate;
use Bigmom\VeEditor\Models\Folder;
use Bigmom\VeEditor\Models\FolderHotspot;
use Bigmom\VeEditor\Models\Hotspot;
use Bigmom\VeEditor\Models\Placeholder;
use Bigmom\VeEditor\Models\Scene;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AssetManager
{
    public function get($path)
    {
        $keys = explode('.', $path);
        $folder = $keys[0] ?? false;
        if (!$folder) throw new \Exception('No folder specified for asset');
        $asset = $keys[1] ?? false;
        if (!$asset) throw new \Exception('No file specified for asset');

        // $template = AssetTemplate::where('name', $keys[1])->whereHas('folder', function ($q) use ($keys) {
        //     $q->where('name', $keys[0]);
        // })->first();
        
        $template = Cache::get("ve-assettemplate-{$keys[0]}-{$keys[1]}");
        if (!$template) {
            $template = AssetTemplate::where('name', $keys[1])->whereHas('folder', function ($q) use ($keys) {
                $q->where('name', $keys[0]);
            })->first();
        }
        
        if (!$template) {
            return null;
        }

        return $template->cached_url ?: $template->url;
    }

    public function sort($sequence)
    {
        $folderId = AssetTemplate::find($sequence[0])->folder_id;

        foreach ($sequence as $asset) {
            if (AssetTemplate::find($asset)->folder_id != $folderId) {
                return false;
            }
        }

        foreach ($sequence as $index => $asset) {
            $asset = AssetTemplate::find($asset);
            $asset->sequence = $index;
            $asset->save();
        }

        return true;
    }

    public function pull($result)
    {
        Asset::truncate();
        AssetTemplate::truncate();
        Folder::truncate();
        Hotspot::truncate();
        FolderHotspot::truncate();
        Placeholder::truncate();
        Scene::truncate();
        foreach ($result->get('asset') as $asset) {
            Asset::create($asset);
        }
        foreach ($result->get('asset_template') as $template) {
            AssetTemplate::create($template);
        }
        foreach ($result->get('folder') as $folder) {
            Folder::create($folder);
        }
        foreach ($result->get('hotspot') as $hotspot) {
            Hotspot::create($hotspot);
        }
        foreach ($result->get('folder_hotspot') as $item) {
            FolderHotspot::create($item);
        }
        foreach ($result->get('placeholder') as $placeholder) {
            Placeholder::create($placeholder);
        }
        foreach ($result->get('scene') as $scene) {
            Scene::create($scene);
        }

        Cache::flush();
        SceneManager::cacheAll();
        AssetManager::cacheAll();

        return true;
    }

    public function checkSizeLimit($uploadedFile)
    {
        $sizeLimit = config('ve.size-limit');

        if ($sizeLimit) {
            if (!is_int($uploadedFile)) {
                $fileSize = $uploadedFile->getSize();
            }
            $occupiedSize = $this->getOccupiedSize;

            $newSize = $occupiedSize + $fileSize;
            if ($newSize > $sizeLimit) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function getOccupiedSize()
    {
        return Cache::rememberForever('occupied-size', function () {
            $occupiedSize = 0;

            foreach (Asset::get() as $asset) {
                $occupiedSize += $asset->size;
            }

            return $occupiedSize;
        });
    }

    public function cacheAll()
    {
        foreach (AssetTemplate::get() as $assetTemplate) {
            $assetTemplate->cached_url = $assetTemplate->url;
            Cache::put("ve-assettemplate-{$assetTemplate->folder->name}-{$assetTemplate->name}", $assetTemplate);
        }
    }
}