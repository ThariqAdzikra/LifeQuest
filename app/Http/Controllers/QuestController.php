<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestController extends Controller
{
    public function index()
    {
        return view('quests.index');
    }

    public function create()
    {
        return view('quests.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        return view('quests.show');
    }

    public function edit(string $id)
    {
        return view('quests.edit');
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}