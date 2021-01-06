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
        foreach (Scene::get() as $scene) {
            Cache::put("ve-scene-{$scene->name}", $scene);
        }
    }
}
