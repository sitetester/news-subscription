<?php

namespace App\Service\Reader\Csv;

use App\Service\Reader\FileReaderInterface;

class CsvFileReader implements FileReaderInterface
{
    private $dataPath;
    private $delimiter;
    private $enclosure;
    private $escape;

    public function __construct(
        string $dataPath,
        string $delimiter = ';',
        string $enclosure = '"',
        string $escape = "\\")
    {
        $this->dataPath = $dataPath;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    public function read(string $filename): array
    {
        $parsedData = [];
        $filePath = $this->dataPath . $filename;
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException('Given file not found on file system.');
        }

        if (($handle = fopen($filePath, 'rb')) !== false) {
            while (($data = fgetcsv(
                    $handle, 1000,
                    $this->delimiter,
                    $this->enclosure,
                    $this->escape)) !== false
            ) {
                if ($data[0] !== null) {
                    $parsedData[] = $data;
                }
            }

            fclose($handle);
        }

        return $parsedData;
    }
}