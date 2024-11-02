<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
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

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|string',
                'note' => 'nullable|string|max:255',
                'upload_type' => 'required|integer',
                'reference_id' => 'required|integer',
                'unique_id' => 'required|string'
            ]);

            if ($request->file) {
                $base64 = $request->file;
    
                $fileHelper = new FileHelper();
                $file = $fileHelper->validateBase64($base64);

                $filePath = $file->store('uploads', 'public');
                $fileName = $file->hashName();
                $originalFileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getClientOriginalExtension();
    
                $uploadFile = new File();
                $uploadFile->file_path = $filePath;
                $uploadFile->file_name = $fileName;
                $uploadFile->origin_file_name = $originalFileName;
                $uploadFile->file_size = $fileSize;
                $uploadFile->file_type = $this->getFileTypeId($fileType);
                $uploadFile->note = $request->input('note');
                $uploadFile->upload_type = $request->input('upload_type');
                $uploadFile->reference_id = $request->input('reference_id');
                $uploadFile->unique_id = $request->input('unique_id');
                $uploadFile->save();
    
                return response()->json(['message' => 'File uploaded successfully']);
            }
            
            return response()->json(['message' => 'File failed to upload.']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function delete(string $unique_id, Request $request)
    {
        $key = config('app.api_key');
        if ($request['key'] !== $key) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $file = File::where('unique_id', $unique_id)->first();
        if (!$file) {
            return response()->json(['error' => 'not found'], 404);
        }

        $fileHelper = new FileHelper();
        $fileHelper->deleteFile($file['file_path']);
        $file->delete();

        return response()->json(['message' => 'File deleted.']);
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
            return response()->file($filePath);
        } else {
            return response()->json(['error' => 'File not found on the server'], 404);
        }
    }
}
