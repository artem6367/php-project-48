<?php

namespace hexlet\code\tests;

use PHPUnit\Framework\TestCase;

use function hexlet\code\gendiff;

class GendiffTest extends TestCase
{
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

	public function testPlainFormater(): void
	{
		$actualJson = gendiff(
			'tests/fixtures/json/file-recurs-1.json',
			'tests/fixtures/json/file-recurs-2.json',
			'plain'
		);
		$expected = <<<EOF
        Property 'common.follow' was added with value: false
        Property 'common.setting2' was removed
        Property 'common.setting3' was updated. From true to null
        Property 'common.setting4' was added with value: 'blah blah'
        Property 'common.setting5' was added with value: [complex value]
        Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
        Property 'common.setting6.ops' was added with value: 'vops'
        Property 'group1.baz' was updated. From 'bas' to 'bars'
        Property 'group1.nest' was updated. From [complex value] to 'str'
        Property 'group2' was removed
        Property 'group3' was added with value: [complex value]
        EOF;

		$this->assertEquals($expected, $actualJson);
	}

	public function testJsonFormater(): void
	{
		$actualJson = gendiff(
			'tests/fixtures/json/file-recurs-1.json',
			'tests/fixtures/json/file-recurs-2.json',
			'json'
		);

		$array = [
			'common' => [
				'follow' => [
					'was' => 'added',
					'value' => false,
				],
				'setting2' => [
					'was' => 'removed',
				],
				'setting3' => [
					'was' => 'updated',
					'from' => true,
					'to' => null,
				],
				'setting4' => [
					'was' => 'added',
					'value' => 'blah blah',
				],
				'setting5' => [
					'was' => 'added',
					'value' => '[complex value]',
				],
				'setting6' => [
					'doge' => [
						'wow' => [
							'was' => 'updated',
							'from' => '',
							'to' => 'so much',
						],
					],
					'ops' => [
						'was' => 'added',
						'value' => 'vops',
					],
				],
			],
			'group1' => [
				'baz' => [
					'was' => 'updated',
					'from' => 'bas',
					'to' => 'bars',
				],
				'nest' => [
					'was' => 'updated',
					'from' => '[complex value]',
					'to' => 'str'
				],
			],
			'group2' => [
				'was' => 'removed',
			],
			'group3' => [
				'was' => 'added',
				'value' => '[complex value]',
			],
		];

		$expected = json_encode($array);

		$this->assertEquals($expected, $actualJson);
	}
}
