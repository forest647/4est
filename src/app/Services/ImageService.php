<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    private int $targetWidth = 1920;
    private int $targetHeight = 1080;

    public function processAndStore(UploadedFile $file, int $creationId): string
    {
        $directory = 'creations/' . str_pad($creationId, 8, '0', STR_PAD_LEFT);
        $filename = $file->getClientOriginalName();
        $path = $directory . '/' . $filename;

        $file->storeAs($directory, $filename, 'public');

        $fullPath = Storage::disk('public')->path($path);
        $this->resizeAndWatermark($fullPath);

        return $path;
    }

    public function deleteCreationImages(int $creationId): void
    {
        $directory = 'creations/' . str_pad($creationId, 8, '0', STR_PAD_LEFT);
        Storage::disk('public')->deleteDirectory($directory);
    }

    public function deleteImage(int $creationId, string $filename): void
    {
        $path = 'creations/' . str_pad($creationId, 8, '0', STR_PAD_LEFT) . '/' . $filename;
        Storage::disk('public')->delete($path);
    }

    private function resizeAndWatermark(string $path): void
    {
        $image = @imagecreatefromjpeg($path);
        if (!$image) {
            $image = @imagecreatefrompng($path);
        }
        if (!$image) {
            return;
        }

        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        $originalAspect = $originalWidth / $originalHeight;
        $targetAspect = $this->targetWidth / $this->targetHeight;

        if ($originalAspect >= $targetAspect) {
            $newHeight = $this->targetHeight;
            $newWidth = (int)($originalWidth / ($originalHeight / $this->targetHeight));
        } else {
            $newWidth = $this->targetWidth;
            $newHeight = (int)($originalHeight / ($originalWidth / $this->targetWidth));
        }

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        $cropped = imagecreatetruecolor($this->targetWidth, $this->targetHeight);
        $cropX = (int)(($newWidth - $this->targetWidth) / 2);
        $cropY = (int)(($newHeight - $this->targetHeight) / 2);
        imagecopy($cropped, $resized, 0, 0, $cropX, $cropY, $this->targetWidth, $this->targetHeight);

        $fontFile = storage_path('app/fonts/awery.smallcaps.ttf');
        if (file_exists($fontFile)) {
            $textColor = imagecolorallocatealpha($cropped, 255, 255, 255, 99);
            imagettftext($cropped, 170, 0, 150, 270, $textColor, $fontFile, '4est');
            imagettftext($cropped, 170, 0, 1350, 950, $textColor, $fontFile, '4est');
        }

        imagejpeg($cropped, $path, 80);

        imagedestroy($image);
        imagedestroy($resized);
        imagedestroy($cropped);
    }
}
