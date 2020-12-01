<?php

namespace Bigmom\VeEditor\Http\Controllers\Vapor;

use Bigmom\VeEditor\Http\Controllers\Controller;
use Bigmom\VeEditor\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function postCreate(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'template-id' => 'required|integer|exists:asset_templates,id',
            'dummy' => 'nullable|boolean',
        ]);

        $asset = new Asset;
        $asset->asset_template_id = $request->input('template-id');

        $path = $asset->storeByKey($request->input('key'));

        if ($path === false) {
            return redirect()
                ->back()
                ->withErrors('error', 'Size limit exceeded.');
        }

        if ($request->has('dummy') && $request->input('dummy') == 1) {
            $asset->dummy = 1;
        } else {
            $asset->dummy = 0;
        }

        $asset->save();

        return $asset->id
            ? response()->json(['status' => 'success', 'message' => 'Asset successfully created.'])
            : response()->json(['status' => 'error', 'message' => 'An error occured.'], 500);
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