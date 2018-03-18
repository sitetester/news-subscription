<?php

namespace App\Service\Writer\Csv;

use App\Service\Writer\FileWriterInterface;

class CsvFileWriter implements FileWriterInterface
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

    public function writeDataToFile(string $filename, array $data): bool
    {
        $filePath = $this->dataPath . $filename;
        $fp = fopen($filePath, 'ab+');

        // multidimensional array ?
        if (\is_array($data[0])) {
            foreach ($data as $datum) {
                fputcsv($fp, $datum, $this->delimiter, $this->enclosure, $this->escape);
            }
        } else {
            fputcsv($fp, $data, $this->delimiter, $this->enclosure, $this->escape);
        }

        return true;
    }

    public function truncateFile(string $filename): void
    {
        $filePath = $this->dataPath . $filename;
        $fp = fopen($filePath, 'rb+');
        ftruncate($fp, 0);
        fclose($fp);
    }
}