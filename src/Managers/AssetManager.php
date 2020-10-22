<?php

namespace Bigmom\VeEditor\Managers;

use Bigmom\VeEditor\Models\Asset;
use Bigmom\VeEditor\Models\AssetTemplate;
use Bigmom\VeEditor\Models\Folder;
use Bigmom\VeEditor\Models\Hotspot;
use Bigmom\VeEditor\Models\Placeholder;
use Bigmom\VeEditor\Models\Scene;

class AssetManager
{
    public function get($keys)
    {
        $template = AssetTemplate::where('name', $keys[1])->whereHas('folder', function ($q) use ($keys) {
            $q->where('name', $keys[0]);
        })->first();
        
        if (!$template) {
            return null;
        }

        $asset = $template->assets()->latest()->first();

        return $asset ? $asset->url : null;
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
        foreach ($result->get('placeholder') as $placeholder) {
            Placeholder::create($placeholder);
        }
        foreach ($result->get('scene') as $scene) {
            Scene::create($scene);
        }

        return true;
    }
}