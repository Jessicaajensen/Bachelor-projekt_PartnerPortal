<?php

namespace App\Http\Controllers;

use App\Services\CsvReader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\RowValidator;

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

        return redirect()->route('uploads.show', $upload->id);
    }

        public function show($id, CsvReader $reader, RowValidator $validator)
    {
        $upload = auth()->user()->partner->uploads()->findOrFail($id);

        $path = Storage::path('uploads/' . $upload->partner_id . '/' . $upload->id . '.csv');

        $csv = $reader->read($path);

               $issues = $validator->validateAll($csv['rows']);

        return view('uploads.show', [
            'upload' => $upload,
            'csv' => $csv,
            'issues' => $issues,
        ]);
    }
}