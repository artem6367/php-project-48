<?php

namespace hexlet\code\tests;

use PHPUnit\Framework\TestCase;

use function hexlet\code\gendiff;

class GendiffTest extends TestCase
{
    public function testGenddiffJson(): void
    {
        $actual = gendiff(__DIR__ . '/fixtures/file1.json', 'tests/fixtures/file2.json');
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

    public function testGendiffYaml(): void
    {
        $actual = gendiff(__DIR__ . '/fixtures/file1.yaml', 'tests/fixtures/file2.yaml');
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
