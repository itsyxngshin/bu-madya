<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image; // Requires intervention/image
use Illuminate\Http\UploadedFile;

trait SmartCompress
{
    /**
     * Compress and store a file (Image OR PDF).
     *
     * @param UploadedFile $file The livewire temporary file
     * @param string $directory Storage folder (e.g., 'transparency')
     * @return string The stored file path
     */
    public function compressAndStore(UploadedFile $file, $directory)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $file->hashName();
        $path = "$directory/$filename";

        // 1. HANDLE IMAGES (Resize + Encode)
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $image = Image::make($file)
                ->resize(1500, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Prevent stretching small images
                })
                ->encode($extension, 75); // 75% Quality is the sweet spot

            Storage::disk('public')->put($path, $image);
            return $path;
        }

        // 2. HANDLE PDFS (Ghostscript)
        if ($extension === 'pdf') {
            // Save the raw file temporarily so Ghostscript can read it
            $tempPath = $file->store('temp', 'local'); 
            $absoluteInput = storage_path('app/' . $tempPath);
            $absoluteOutput = storage_path('app/public/' . $path);

            // Create directory if not exists
            if (!file_exists(dirname($absoluteOutput))) {
                mkdir(dirname($absoluteOutput), 0755, true);
            }

            // Ghostscript Command
            // -dPDFSETTINGS=/ebook  -> 150dpi (Great for reading, small size)
            // -dPDFSETTINGS=/screen -> 72dpi (Smallest size, maybe blurry for text)
            $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile='{$absoluteOutput}' '{$absoluteInput}'";
            
            // Execute command
            shell_exec($command);

            // Cleanup temp file
            @unlink($absoluteInput);

            // Check if compression worked (file exists). If not, fallback to normal store.
            if (file_exists($absoluteOutput)) {
                return $path;
            } else {
                // Ghostscript failed (maybe not installed?), store normally
                return $file->store($directory, 'public');
            }
        }

        // 3. FALLBACK (Word docs, Excel, etc.)
        return $file->store($directory, 'public');
    }
} 