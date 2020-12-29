<?php

namespace Bigmom\VeEditor\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getLogin()
    {
        return view('veeditor::auth.login');
    }

    public function postLogin(Request $request)
    {
        $success = Auth::guard('ve-editor')->attempt($request->only(['email', 'username', 'password']), true);

        return Auth::guard('ve-editor')->user()
            ? redirect()
                ->route('ve-editor.scene.getIndex')
            : redirect()
                ->back()
                ->withErrors('Invalid credentials.');
    }
}