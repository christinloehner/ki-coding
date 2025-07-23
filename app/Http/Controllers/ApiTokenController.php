<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('use api')) {
            abort(403, 'This action is unauthorized.');
        }

        $tokens = Auth::user()->tokens;

        return view('dashboard.api-tokens', compact('tokens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('use api')) {
            abort(403, 'This action is unauthorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = Auth::user()->createToken($request->name);

        return back()->with('status', 'API Token created successfully. Your token is: ' . $token->plainTextToken);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('use api')) {
            abort(403, 'This action is unauthorized.');
        }

        // Sichere Token-Löschung - nur eigene Tokens
        $token = Auth::user()->tokens()->findOrFail($id);
        $tokenName = $token->name;
        $token->delete();

        return back()->with('status', "API Token '{$tokenName}' wurde erfolgreich widerrufen.");
    }
}
