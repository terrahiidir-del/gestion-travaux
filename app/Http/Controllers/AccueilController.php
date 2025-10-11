<?php

namespace App\Http\Controllers;

use App\Models\accueil;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccueilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    return view('Accueil.index');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(accueil $accueil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(accueil $accueil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, accueil $accueil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(accueil $accueil)
    {
        //
    }
}
