<?php

namespace Bigmom\VeEditor\Http\Controllers;

use Bigmom\VeEditor\Http\Controllers\Controller;
use Bigmom\VeEditor\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function getIndex(Request $request)
    {
        $request->validate([
            'folder-type' => 'required|integer|in:0,1,2',
            'search' => 'nullable|string|max:191',
        ]);

        $folders = Folder::where('folder_type', $request->input('folder-type'))
            ->when($request->input('search'), function ($query, $search) {
                return $query->where('name', 'like', "%$search%");
            })->paginate(15);

        return view('veeditor::folder.index', compact('folders'));
    }

    public function postCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'folder-type' => 'required|integer|in:0,1,2',
        ]);

        $folder = new Folder;
        $folder->name = $request->input('name');
        $folder->folder_type = $request->input('folder-type');
        $folder->save();

        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Folder created.');
    }

    public function postUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'folder-id' => 'required|integer|exists:folders,id',
        ]);

        $folder = Folder::find($request->input('folder-id'));
        if ($folder->type_name != 'Static Asset') {
            return redirect()
                ->back()
                ->with('error', 'error')
                ->with('message', 'Folder is not static asset folder.');
        }
        $folder->name = $request->input('name');
        $folder->save();

        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Folder updated.');
    }

    public function postDelete(Request $request)
    {
        $request->validate([
            'folder-id' => 'required|integer|exists:folders,id',
        ]);

        $folder = Folder::find($request->input('folder-id'));
        $folder->delete();
        
        return redirect()
            ->back()
            ->with('success', 'success')
            ->with('message', 'Folder deleted');
    }

    public function getShow(Folder $folder, Request $request)
    {
        $templates = $folder->assetTemplates()->when($request->input('search'), function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('sequence', 'asc')->paginate(15);
        return view('veeditor::folder.show', compact('folder', 'templates'));
    }
}
