<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Models\File;
use App\Http\Helpers\FileHelper;

class FileController extends Controller
{
    public function show(string $unique_id)
    {
        $file = File::where('unique_id', $unique_id)->first();

        if ($file) {
            return $this->sendFileResponse($file);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function delete(string $unique_id, Request $request)
    {
        $environment = App::environment();
        return response()->json(['data' => $environment]);
    }

    private function getFileTypeId($extension)
    {
        $types = [
            'jpg' => 1,
            'png' => 2,
            'pdf' => 3,
            'doc' => 4,
            'docx' => 5,
        ];

        return $types[$extension] ?? 0;
    }

    private function sendFileResponse($file)
    {
        $filePath = storage_path('app/public/' . $file->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath, $file->origin_file_name);
        } else {
            return response()->json(['error' => 'File not found on the server'], 404);
        }
    }
}
