<?php

namespace Bigmom\VeEditor\Http\Controllers;

use Bigmom\VeEditor\Facades\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContentController extends Controller
{
    public function getIndex()
    {
        return view('veeditor::content.index');
    }

    public function pull()
    {
        $response = Http::withBasicAuth(config('ve.api_username'), config('ve.api_password'))->get(config('ve.pull_url'));

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
