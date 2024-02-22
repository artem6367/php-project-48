<?php

namespace hexlet\code;

use function hexlet\code\Parsers\parseFile;

function gendiff(string $file1, string $file2): string
{
    $json1 = parseFile($file1);
    $json2 = parseFile($file2);

    $keys = array_unique([...array_keys($json1), ...array_keys($json2)]);
    sort($keys);

    $diff = [];
    foreach ($keys as $key) {
        $val1 = getStrValue($key, $json1);
        $val2 = getStrValue($key, $json2);

        if (array_key_exists($key, $json1) && !array_key_exists($key, $json2)) {
            $diff[] = "  - $key: $val1";
        } elseif (!array_key_exists($key, $json1) && array_key_exists($key, $json2)) {
            $diff[] = "  + $key: $val2";
        } elseif ($json1[$key] == $json2[$key]) {
            $diff[] = "    $key: $val1";
        } else {
            $diff[] = "  - $key: $val1";
            $diff[] = "  + $key: $val2";
        }
    }

    return '{' . PHP_EOL . implode(PHP_EOL, $diff) . PHP_EOL . '}';
}

function getStrValue(string $key, array $json): ?string
{
    $value = $json[$key] ?? null;
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}
