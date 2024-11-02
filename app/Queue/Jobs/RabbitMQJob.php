<?php

namespace App\Queue\Jobs;

use Illuminate\Support\Facades\Log;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob as BaseJob;
use App\Http\Controllers\FileController;
use App\Http\Helpers\FileHelper;
use App\Models\File;
use App\Jobs\ProcessRabbitMQMessage;

class RabbitMQJob extends BaseJob
{
    public function fire()
    {
        $message = $this->getRawBody();  
        $json = json_decode($message, true);
        $fileHelper = new FileHelper();

        $existing = File::where('unique_id', $json['unique_id'])->first();
        if ($existing) {
            $fileHelper->deleteFile($existing['file_path']);
            $existing->delete();
        }

        $file = $fileHelper->validateBase64($json['file']);

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
        $uploadFile->file_type = 1;
        $uploadFile->note = $json['note'];
        $uploadFile->upload_type = $json['upload_type'];
        $uploadFile->reference_id = $json['reference_id'];
        $uploadFile->unique_id = $json['unique_id'];
        $uploadFile->save();

        $this->delete();
    }

    public function getName()
    {
        return '';
    }
}