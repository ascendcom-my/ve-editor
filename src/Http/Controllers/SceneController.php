<?php

namespace Bigmom\VeEditor\Http\Controllers;

use Bigmom\VeEditor\Models\Folder;
use Bigmom\VeEditor\Models\Hotspot;
use Bigmom\VeEditor\Models\Placeholder;
use Bigmom\VeEditor\Models\Scene;
use DB;
use Illuminate\Http\Request;

class SceneController extends Controller
{
    public function getIndex()
    {
        $scenes = Scene::get();
        return view('vendor.ve-editor.scene.index', compact('scenes'));
    }

    public function postCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:scenes,name',
            'type' => 'required|integer|min:0|max:2',
            'file' => 'required|file',
        ]);

        config([
            //'filesystems.disks.s3.visibility' => 'public',
            'filesystems.disks.s3.options' => ['CacheControl' => 'max-age=315360000, no-transform, public'],
        ]);

        $scene = new Scene;
        $scene->name = $request->input('name');
        $scene->type = $request->input('type');
        if ($scene->typeName == '3D Model') {
            $scene->extras = '{"scene": "0,0,0", "camera": "-0.84,0,-1.8", "polarRange":
                "Math.PI/3,Math.PI/1.8", "distanceRange": "2.2,2.2"}';
        }
        $scene->store($request->file('file'));
        $scene->save();

        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Scene created.');
    }

    public function postUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'type' => 'required|integer|min:0|max:2',
            'file' => 'nullable|file',
            'scene-id' => 'required|integer|exists:scenes,id',
        ]);

        $scene = Scene::find($request->input('scene-id'));
        $scene->name = $request->input('name');
        $scene->type = $request->input('type');
        
        if ($request->has('file') && $request->file('file')) {
            $scene->store($request->file('file'));
        }

        $scene->save();

        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Scene updated.');
    }

    public function postDelete(Request $request)
    {
        $request->validate([
            'scene-id' => 'required|integer|exists:scenes,id'
        ]);

        $scene = Scene::find($request->input('scene-id'));
        $scene->deleteAsset()->delete();

        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Scene deleted.');
    }

    public function getShow(Scene $scene)
    {
        $scene->hotspots = $scene->hotspots;
        return view($scene->blade, compact('scene'));
    }
    
    public function postManage(Scene $scene, Request $request)
    {
        $request->validate([
            'hotspots' => 'nullable|json',
            'placeholders' => 'nullable|json',
            'extras' => 'nullable|json',
        ]);

        DB::transaction(function () use ($scene, $request) {
            $hotspots = json_decode($request->input('hotspots'), true);

            $hotspots_array = [];
            foreach ($hotspots as $index => $hotspot_item) {
                $hotspot_item = json_decode(json_encode($hotspot_item), false);
                if (isset($hotspot_item->id) && !empty($hotspot_item->id)) {
                    $hotspot = Hotspot::find($hotspot_item->id);
                } else {
                    $hotspot = new Hotspot;
                    $hotspot->scene_id = $scene->id;
                }
                $hotspot->position = isset($hotspot_item->position) ? json_encode($hotspot_item->position) : '';
                $hotspot->name = $hotspot_item->name;
                $hotspot->meta = $hotspot_item->meta;
                $hotspot->save();
                if (isset($hotspot_item->folders)) {
                    $folders = explode(",", $hotspot_item->folders && $hotspot_item->folders != '' ? $hotspot_item->folders : '');
                    if (!empty($folders[0]) && $folders[0] != '') {
                        $assets = Folder::whereIn('id', $folders)->get()->pluck('copyable')->implode(PHP_EOL);
                        $hotspot_item->medias = $assets;
                        $hotspot->folders()->sync([]);
                        foreach ($folders as $folder) {
                            if (Folder::find($folder)) {
                                $hotspot->folders()->attach($folder);
                            }
                        }
                    }
                }
                $hotspot->medias = $hotspot_item->medias;
                $hotspot->save();
                array_push($hotspots_array, $hotspot);
            }

            Hotspot::where('scene_id', $scene->id)->whereNotIn('id', collect($hotspots_array)->pluck('id'))->delete();

            $placeholders = json_decode($request->input('placeholders'), true);

            $placeholders_array = [];
            foreach ($placeholders as $index => $placeholder_item) {
                $placeholder_item = json_decode(json_encode($placeholder_item), false);
                if (isset($placeholder_item->id) && !empty($placeholder_item->id)) {
                    $placeholder = Placeholder::find($placeholder_item->id);
                } else {
                    $placeholder = new Placeholder;
                    $placeholder->scene_id = $scene->id;
                }
                $placeholder->position = json_encode($placeholder_item->position);
                $placeholder->url = $placeholder_item->url;
                $placeholder->save();
                array_push($placeholders_array, $placeholder);
            }

            Placeholder::where('scene_id', $scene->id)->whereNotIn('id', collect($placeholders_array)->pluck('id'))->delete();

            $scene->extras = $request->input('extras');
            $scene->save();
            return true;
        });

        return redirect()->back()
            ->with('success', 'Success')
            ->with('message', 'Successfully updated scene.')
            ;
    }
}
