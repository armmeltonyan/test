<?php

namespace App\Services\Image;

use App\Dto\ImageDto;
use Exception;

class ImageSqueezeService
{
    /**
     * @throws Exception
     */
    public function squeeze(string $filePath): ImageDto
    {
        $scale = 0.5;
        $quality = 75;
        list($width, $height, $imageType) = getimagesize($filePath);

        // Calculate new dimensions based on the scale factor
        $newWidth = $width * $scale;
        $newHeight = $height * $scale;
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        $source = match ($imageType) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($filePath),
            IMAGETYPE_PNG  => imagecreatefrompng($filePath),
            IMAGETYPE_GIF  => imagecreatefromgif($filePath),
            default        => null
        };

        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start(); // Start output buffering
        match ($imageType) {
            IMAGETYPE_JPEG => imagejpeg($newImage, null, $quality),
            IMAGETYPE_PNG  => imagepng($newImage, null, 9 - intval($quality / 10)),
            IMAGETYPE_GIF  => imagegif($newImage, null),
        };
        $imageData = ob_get_clean(); // Get the image data and clean the buffer

        imagedestroy($newImage);
        imagedestroy($source);

        return new ImageDto(
            image: $imageData,
            path: uniqid() . image_type_to_extension($imageType)
        );
    }
}
