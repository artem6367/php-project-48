<?php

namespace hexlet\code\tests;

use PHPUnit\Framework\TestCase;

use function hexlet\code\gendiff;

class GendiffTest extends TestCase
{
    public function testCompareFlatFiles(): void
    {
        $actualJson = gendiff(__DIR__ . '/fixtures/json/file1.json', 'tests/fixtures/json/file2.json');
        $actualYaml = gendiff(__DIR__ . '/fixtures/yaml/file1.yaml', 'tests/fixtures/yaml/file2.yaml');

        $expected = <<<EOF
        {
          - follow: false
            host: hexlet.io
          - proxy: 123.234.53.22
          - timeout: 50
          + timeout: 20
          + verbose: true
        }
        EOF;

        $this->assertEquals($expected, $actualJson);
        $this->assertEquals($expected, $actualYaml);
    }

    public function testRecursiveComparison(): void
    {
        $actualJson = gendiff('tests/fixtures/json/file-recurs-1.json', 'tests/fixtures/json/file-recurs-2.json');
        $actualYaml = gendiff('tests/fixtures/yaml/file-recurs-1.yaml', 'tests/fixtures/yaml/file-recurs-2.yaml');

        $expected = <<<EOF
        {
            common: {
              + follow: false
                setting1: Value 1
              - setting2: 200
              - setting3: true
              + setting3: null
              + setting4: blah blah
              + setting5: {
                    key5: value5
                }
                setting6: {
                    doge: {
                      - wow: 
                      + wow: so much
                    }
                    key: value
                  + ops: vops
                }
            }
            group1: {
              - baz: bas
              + baz: bars
                foo: bar
              - nest: {
                    key: value
                }
              + nest: str
            }
          - group2: {
                abc: 12345
                deep: {
                    id: 45
                }
            }
          + group3: {
                deep: {
                    id: {
                        number: 45
                    }
                }
                fee: 100500
            }
        }
        EOF;

        $this->assertEquals($expected, $actualJson);
        $this->assertEquals($expected, $actualYaml);
    }
}
