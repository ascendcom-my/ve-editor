<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetTemplate;
use App\Models\Folder;
use App\Models\FolderHotspot;
use App\Models\Hotspot;
use App\Models\Placeholder;
use App\Models\Scene;
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
