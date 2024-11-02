<?php

namespace App\Http\Helpers;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileHelper
{
  public function validateBase64(string $base64data): UploadedFile
  {
    if (str_contains($base64data, ';base64')) {
      list(, $base64data) = explode(';', $base64data);
      list(, $base64data) = explode(',', $base64data);
    }

    if (!base64_decode($base64data, true)) {
      return false;
    }

    if (base64_encode(base64_decode($base64data)) !== $base64data) {
      return false;
    }

    $fileBinaryData = base64_decode($base64data);

    $tmpFileName = tempnam(sys_get_temp_dir(), 'medialibrary');
    file_put_contents($tmpFileName, $fileBinaryData);

    $tmpFileObject = new File($tmpFileName);

    $path = $tmpFileObject->getPathname();
    $file = new UploadedFile(
      $path,
      $tmpFileObject->getFilename(),
      $tmpFileObject->getMimeType(),
      0,
      true
    );

    return $file;
  }
}
