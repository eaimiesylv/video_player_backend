<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function uploadImage($file, $path): string
    {

        // Generate a unique prefix
        $uniquePrefix = uniqid().'_';

        // Original file name
        $originalName = $file->getClientOriginalName();

        // Remove all characters except a-z and convert to lowercase
        $cleanName = preg_replace('/[^a-zA-Z]/', '', $originalName); // Remove special characters
        $cleanName = strtolower($cleanName); // Convert to lowercase

        // Combine the unique prefix and the cleaned-up file name
        $fileName = $uniquePrefix . $cleanName;

        // Calculate the maximum length for the original name to keep the total length within 255 characters
        $maxLength = 250 - strlen($fileName);

        // Truncate the original name if it's too long
        if (strlen($fileName) > $maxLength) {
            $fileName = substr($fileName, 0, $maxLength);
        }

        // Store the file
        $filePath = $file->storeAs($path, $fileName, 'root');

        // Return the file URL
        return Storage::disk('root')->url($filePath);
    }

}
