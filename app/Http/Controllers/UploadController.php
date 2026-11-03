<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function create()
    {
        return view('uploads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $user = auth()->user();
        $file = $request->file('file');

        $upload = $user->partner->uploads()->create([
            'user_id' => $user->id,
            'filename' => $file->getClientOriginalName(),
            'status' => 'uploaded',
        ]);

        $file->storeAs('uploads/' . $user->partner_id, $upload->id . '.csv');

        return redirect()->route('uploads.create')
            ->with('success', 'Filen er modtaget: ' . $upload->filename);
    }
}