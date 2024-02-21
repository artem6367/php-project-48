<?php

namespace hexlet\code\tests;

use PHPUnit\Framework\TestCase;
use function hexlet\code\gendiff;

class GendiffTest extends TestCase
{
    public function testGenddiff1()
    {
        $actual = gendiff(__DIR__ . '/data/file1.json', 'tests/data/file2.json');
        $expected = "{\n"
            . "  - follow: false\n"
            . "    host: hexlet.io\n"
            . "  - proxy: 123.234.53.22\n"
            . "  - timeout: 50\n"
            . "  + timeout: 20\n"
            . "  + verbose: true\n"
            . "}";

        $this->assertEquals($expected, $actual);
    }
}
