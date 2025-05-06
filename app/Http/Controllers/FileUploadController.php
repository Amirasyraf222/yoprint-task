<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessCsvFile;

class FileUploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->store('uploads');

        $file = FileUpload::create([
            'file_name' => basename($path),
            'uploaded_at' => now(),
            'status' => 'pending',
        ]);

        // Dispatch background job
        ProcessCsvFile::dispatch($file);

        return back()->with('success', 'File uploaded successfully!');
    }

    public function list()
    {
        return response()->json(FileUpload::orderBy('created_at', 'desc')->get());
    }
}
