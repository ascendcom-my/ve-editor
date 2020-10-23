<?php

namespace Bigmom\VeEditor\Http\Controllers;

use Bigmom\VeEditor\Facades\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContentController extends Controller
{
    public function getIndex()
    {
        return view('vendor.ve-editor.content.index');
    }

    public function pull()
    {
        $response = Http::withToken(config('ve.api_token'))->get(config('ve.pull_url'));

        $result = collect($response->json());

        $success = Asset::pull($result);
        
        return $success ? 
            redirect()
                ->back()
                ->with('success', 'success')
                ->with('message', 'Pulled.')
            : redirect()
                ->back()
                ->with('error', 'error')
                ->with('message', 'Error.');
    }
}
