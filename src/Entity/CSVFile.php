<?php

namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;

class CSVFile
{
    /**
     * @Assert\File(mimeTypes={ "text/plain" })
     */
    private $csvFile;

    public function getCsvFile()
    {
        return $this->csvFile;
    }

    public function setCsvFile($csvFile): void
    {
        $this->csvFile = $csvFile;
    }
}