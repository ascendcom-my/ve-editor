<?php

namespace Bigmom\VeEditor\Http\Controllers\Vapor;

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
            'folder-id' => 'required|integer|exists:folders,id'
        ]);

        if (Folder::find($request->input('folder-id'))->assetTemplates()->where('name', $request->input('name'))->exists()) {
            return redirect()
                ->back()
                ->withErrors('error', 'Name already used inside this folder.');
        }

        $template = new AssetTemplate;
        $template->name = $request->input('name');
        $template->file_type = $request->input('type');
        $template->requirement = $request->input('requirement');
        $template->folder_id = $request->input('folder-id');
        $template->sequence = AssetTemplate::where('folder_id', $template->folder_id)->count();
        $template->save();

        return $template->id
            ? response()->json(['status' => 'success', 'message' => 'Asset template successfully created.', 'template-id' => $template->id])
            : response()->json(['status' => 'error', 'message' => 'An error occured.'], 500);
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
        if ($template->name !== $request->input('name')) {
            if ($template->folder->assetTemplates()->where('name', $request->input('name'))->exists()) {
                return redirect()
                    ->back()
                    ->withErrors('Name already used inside this folder.');
            } 
        }
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
        $assets = $template->assets()->orderBy('updated_at', 'desc')->paginate(15);
        return view('veeditor::asset-template.vapor-show', compact('template', 'assets'));
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
