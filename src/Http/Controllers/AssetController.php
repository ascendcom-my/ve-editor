<?php

namespace Bigmom\VeEditor\Http\Controllers;

use Bigmom\VeEditor\Http\Controllers\Controller;
use Bigmom\VeEditor\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function postCreate(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'template-id' => 'required|integer|exists:asset_templates,id',
        ]);

        $asset = new Asset;
        $asset->asset_template_id = $request->input('template-id');

        $path = $asset->store($request->file('file'));

        if ($path === false) {
            return redirect()
                ->back()
                ->withErrors('error', 'Size limit exceeded.');
        }

        if ($request->has('dummy') && $request->input('dummy')) {
            $asset->dummy = 1;
        } else {
            $asset->dummy = 0;
        }

        $asset->save();

        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Asset created.');
    }

    public function postDelete(Request $request)
    {
        $request->validate([
            'asset-id' => 'required|integer|exists:assets,id',
        ]);

        $asset = Asset::find($request->input('asset-id'));
        $asset->deleteAsset()->delete();
        
        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Asset deleted.');
    }
}