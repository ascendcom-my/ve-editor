<?php

namespace Bigmom\VeEditor\Http\Controllers;

use Bigmom\VeEditor\Http\Controllers\Controller;
use Bigmom\VeEditor\Facades\Asset as AssetManager;
use Bigmom\VeEditor\Models\Asset;
use Bigmom\VeEditor\Models\AssetTemplate;
use Bigmom\VeEditor\Models\Folder;
use Illuminate\Http\Request;

class AssetTemplateController extends Controller
{
    public function postCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'type' => 'required|integer',
            'requirement' => 'required|string|max:191',
            'file' => 'nullable|file',
            'dummy' => 'nullable',
            'folder-id' => 'required|integer|exists:folders,id'
        ]);

        $template = new AssetTemplate;
        $template->name = $request->input('name');
        $template->file_type = $request->input('type');
        $template->requirement = $request->input('requirement');
        $template->folder_id = $request->input('folder-id');
        $template->sequence = AssetTemplate::where('folder_id', $template->folder_id)->count();
        $template->save();

        if ($request->has('file')) {
            $asset = new Asset;
            $asset->asset_template_id = $template->id;
            $asset->store($request->file('file'));
    
            if ($request->has('dummy') && $request->input('dummy')) {
                $asset->dummy = 1;
            } else {
                $asset->dummy = 0;
            }
    
            $asset->save();
        }

        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Asset template created.');
    }

    public function postUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'type' => 'required|integer',
            'requirement' => 'required|string|max:191',
            'template-id' => 'required|integer|exists:asset_templates,id', 
        ]);

        $template = AssetTemplate::find($request->input('template-id'));
        $template->name = $request->input('name');
        $template->file_type = $request->input('type');
        $template->requirement = $request->input('requirement');
        $template->save();

        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Asset template updated.');
    }

    public function postDelete(Request $request)
    {
        $request->validate([
            'template-id' => 'required|integer|exists:asset_templates,id',
        ]);

        $template = AssetTemplate::find($request->input('template-id'));
        if (count($template->assets)) {
            return redirect()
                ->back()
                ->with('error', 'error')
                ->with('message', 'Asset template not empty.');
        }
        $template->delete();

        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Asset template deleted.');
    }
    
    public function getShow(AssetTemplate $template)
    {
        return view('vendor.ve-editor.asset-template.show', compact('template'));
    }

    public function postSort(Request $request)
    {
        $request->validate([
            'sequence' => 'required|string|max:191',
        ]);

        $sequence = explode(',', $request->input('sequence'));
        if (count($sequence) <= 1) return response()->json(['error' => 'Not enough elements.'], 500);
        
        $success = AssetManager::sort($sequence);

        return $success ? response()->json(['success' => 'success'])
            : response()->json(['error' => 'error'], 500);
    }
}
