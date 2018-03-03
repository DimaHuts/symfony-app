<?php

namespace App\Service;


interface UploadServiceInterface
{
    public static function removeFiles($removeFiles): void;

    public static function transferFiles($transferFiles): array;
}