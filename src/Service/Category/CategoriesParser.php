<?php

namespace App\Service\Category;

use App\Entity\NewsCategory;
use App\Service\Reader\FileReaderInterface;

class CategoriesParser
{
    private $fileReader;

    public function __construct(FileReaderInterface $fileReader)
    {
        $this->fileReader = $fileReader;
    }

    /**
     * @return NewsCategory[]
     */
    public function parse(string $filename): array
    {
        return $this->fileReader->read($filename);
    }

}