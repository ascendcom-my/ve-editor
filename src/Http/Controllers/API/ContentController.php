<?php

namespace Bigmom\VeEditor\Http\Controllers\API;

use Bigmom\VeEditor\Http\Controllers\Controller;
use Bigmom\VeEditor\Models\Asset;
use Bigmom\VeEditor\Models\AssetTemplate;
use Bigmom\VeEditor\Models\Folder;
use Bigmom\VeEditor\Models\FolderHotspot;
use Bigmom\VeEditor\Models\Hotspot;
use Bigmom\VeEditor\Models\Placeholder;
use Bigmom\VeEditor\Models\Scene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    public function getContent()
    {
        $asset = Asset::get();
        $asset_template = AssetTemplate::get();
        $folder = Folder::get();
        $hotspot = Hotspot::get();
        $folder_hotspot = FolderHotspot::get();
        $placeholder = Placeholder::get();
        $scene = Scene::get();

        return response()->json(compact('asset', 'asset_template', 'folder', 'hotspot', 'folder_hotspot', 'placeholder', 'scene'));
    }
}
