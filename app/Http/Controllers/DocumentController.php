<?php

namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;



class DocumentController extends Controller
{

    public function showDoc($id)
{
    $selected = Document::findOrFail($id);
    $filePath = Storage::path('public/documents/' . $selected->fileName);

    return response()->file($filePath, [
        'Content-Type' => mime_content_type($filePath),
    ]);
    
}




public function download($id, $hash)
{
    $file = Document::findOrFail($id);

    $filePath = storage_path('app/public/' . $file->path);

    // Validate the hash before proceeding
    $expectedHash = md5($file->id . time());
    if ($hash !== $expectedHash) {
        abort(403, 'Unauthorized');
    }

    // Set the appropriate headers for the download response
    $headers = [
        'Content-Type' => 'application/octet-stream',
        'Content-Disposition' => 'attachment; filename="' . $file->fileName . '"',
    ];

    // Create a download response with the file's contents and headers
    return response()->download($filePath, $file->fileName, $headers);
}

public function uniqueDownload($id, $token)
    {
        $file = Document::findOrFail($id);

        $filePath = storage_path('app/public/' . $file->path);

        // Set the appropriate headers for the download response
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $file->fileName . '"',
        ];

        // Create a download response with the file's contents and headers
        return response()->download($filePath, $file->fileName, $headers);
    }
}
