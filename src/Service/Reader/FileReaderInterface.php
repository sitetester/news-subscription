<?php

namespace App\Service\Reader;

interface FileReaderInterface
{
    /**
     * @param string $filename - Could be in ANY format (CSV, XML, ...)
     * @return array
     */
    public function read(string $filename): array;
}