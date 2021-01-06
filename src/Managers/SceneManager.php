<?php

namespace Bigmom\VeEditor\Managers;

use Bigmom\VeEditor\Models\Scene;
use Illuminate\Support\Facades\Cache;

class SceneManager
{
    public function get($name)
    {
        $scene = Cache::get("ve-scene-$name");

        if (!$scene) {
            $scene = Scene::where('name', $name)->first();
        }

        return $scene;
    }

    public function cacheAll()
    {
        $sceneKeys = [];
        foreach (Scene::get() as $scene) {
            $key = "ve-scene-{$scene->name}";
            Cache::put($key, $scene);
            array_push($sceneKeys, $key);
        }
        Cache::put('ve-keys-scene', $sceneKeys);
    }

    public function removeObsoleteCache()
    {
        $keys = Cache::get('ve-keys-scene');
        if ($keys) {
            foreach ($keys as $key) {
                $cacheScene = Cache::get($key);
                if ($cacheScene) {
                    $dbScene = Scene::where('name', $cacheScene->name)->first();

                    if (!$dbScene) {
                        Cache::forget($key);
                    }
                }
            }
        }
        return false;
    }
}
