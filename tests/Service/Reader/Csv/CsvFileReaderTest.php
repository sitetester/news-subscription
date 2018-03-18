<?php

namespace App\Tests\Service\Reader\Csv;

use App\Service\Reader\Csv\CsvFileReader;
use PHPUnit\Framework\TestCase;

class CsvFileReaderTest extends TestCase
{
    /**
     * @var CsvFileReader
     */
    protected $csvFileReader;

    public function testRead(): void
    {
        self::assertNotEmpty(
            $this->csvFileReader->read('categories.csv')
        );
    }

    protected function setUp(): void
    {
        $this->csvFileReader = new CsvFileReader(
            __DIR__ . '/Resources/'
        );
    }
}