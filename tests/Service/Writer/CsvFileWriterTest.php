<?php

namespace App\Tests\Service\Writer;

use App\Service\Writer\Csv\CsvFileWriter;
use PHPUnit\Framework\TestCase;

class CsvFileWriterTest extends TestCase
{
    /**
     * @var CsvFileWriter
     */
    protected $csvFileWriter;

    public function testWriteDataToFile(): void
    {
        $list = [
            ['aaa', 'bbb', 'ccc', 'dddd'],
            ['123', '456', '789'],
            ['"aaa"', '"bbb"']
        ];

        self::assertTrue(
            $this->csvFileWriter->writeDataToFile('dummy.csv', $list)
        );
    }

    protected function setUp(): void
    {
        $this->csvFileWriter = new CsvFileWriter(
            __DIR__ . '/Resources/'
        );
    }
}