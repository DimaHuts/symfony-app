<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class UploadService
 *
 * @author Dmitry Huts
 */
class UploadService
{

    public static function removeFiles($removeFiles): void
    {
        $files = [];
        if (!is_array($removeFiles))
        {
            $removeFiles = [$removeFiles];
        }

        foreach ($removeFiles as $image)
        {
            array_push($files, $image);
        }

        (new Filesystem())->remove($files);
    }

    public static function transferFiles($transferFiles): array
    {
        $fileNames = [];
        $path = 'uploads/products-images';
        if (!is_array($transferFiles))
        {
            $transferFiles = [$transferFiles];
        }
        foreach ($transferFiles as $file)
        {
            $fileName = $path . '/' . md5(uniqid()).'.'.$file->guessExtension();
            $file->move($path, $fileName);
            array_push($fileNames, $fileName);
        }

        return $fileNames;
    }

}