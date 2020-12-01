<?php

namespace Bigmom\VeEditor\Http\Controllers\Vapor;

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
            'name' => 'required|string|max:191|unique:folders,name',
            'folder-type' => 'required|integer|in:0,1,2',
        ]);
        
        if ($request->input('name') === 'Scenes') {
            return redirect()
                ->back()
                ->withErrors('error', 'This name is invalid.');
        }

        $folder = new Folder;
        $folder->name = $request->input('name');
        $folder->folder_type = $request->input('folder-type');
        $folder->save();

        return redirect()
            ->back()
            ->with('success', 'Folder successfully created.')
            ->with('message', 'Folder created.');
    }

    public function postUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'folder-id' => 'required|integer|exists:folders,id',
        ]);

        $folder = Folder::find($request->input('folder-id'));
        if ($folder->name === 'Scenes') {
            return redirect()
                ->back()
                ->withErrors('error', 'This folder cannot be updated.');
        }
        if ($folder->name !== $request->input('name')) {
            if (Folder::where('name', $request->input('name'))->exists()) {
                return redirect()
                    ->back()
                    ->withErrors('error', 'This name is already used.');
            }
        }
        $folder->name = $request->input('name');
        $folder->save();

        return redirect()
            ->back()
            ->with('success', 'Folder successfully updated.');
    }

    public function postDelete(Request $request)
    {
        $request->validate([
            'folder-id' => 'required|integer|exists:folders,id',
        ]);

        $folder = Folder::find($request->input('folder-id'));
        if ($folder->name === 'Scenes') {
            return redirect()
                ->back()
                ->withErrors('error', 'This folder cannot be deleted.');
        }
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
        return view('veeditor::folder.vapor-show', compact('folder', 'templates'));
    }
}
