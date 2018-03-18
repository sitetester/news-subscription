<?php

namespace App\Service\Writer;

interface FileWriterInterface
{
    /**
     * Data could be written in ANY format
     */
    public function writeDataToFile(string $filename, array $data): bool;

    public function truncateFile(string $filename);
}