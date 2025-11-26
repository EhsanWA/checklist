<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPinController extends Controller
{
    public function show()
    {
        return view('admin.pin');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'pin' => ['required', 'string'],
        ]);

        $expected = (string) config('admin.pin', '');
        if ($expected !== '' && hash_equals($expected, (string) $request->pin)) {
            $request->session()->put('is_admin', true);

            $intended = $request->session()->pull('intended_url');
            return redirect()->to($intended ?? route('admin.menu'))
                ->with('success', 'Toegang verleend.');
        }

        return back()->withInput()->withErrors([
            'pin' => 'Onjuiste code.',
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        return redirect()->route('reports.index')->with('success', 'Uitgelogd.');
    }
}
