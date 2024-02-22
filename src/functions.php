<?php

namespace hexlet\code;

use function hexlet\code\Parsers\parseFile;

function gendiff(string $file1, string $file2): string
{
    $json1 = parseFile($file1);
    $json2 = parseFile($file2);

    $diff = getDiff($json1, $json2);

    return $diff;
}

function getDiff(array $data1, array $data2, $spaceCount = 0): string
{
    $keys = array_unique([...array_keys($data1), ...array_keys($data2)]);
    sort($keys);

    $diff = [];
    $newSpaceCount = $spaceCount + 4;
    foreach ($keys as $key) {
        $val1 = getStrValue($key, $data1);
        $val2 = getStrValue($key, $data2);

        if (array_key_exists($key, $data1) && !array_key_exists($key, $data2)) {
            if (is_array($val1)) {
                $diff[] = "  - $key: " . getDiff($val1, $val1, $newSpaceCount);
            } else {
                $diff[] = "  - $key: $val1";
            }
        } elseif (!array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
            if (is_array($val2)) {
                $diff[] = "  + $key: " . getDiff($val2, $val2, $newSpaceCount);
            } else {
                $diff[] = "  + $key: $val2";
            }
        } elseif ($val1 != $val2) {
            if (is_array($val1) && is_array($val2)) {
                $diff[] = "    $key: " . getDiff($val1, $val2, $newSpaceCount);
            } elseif (is_array($val1) && !is_array($val2)) {
                $diff[] = "  - $key: " . getDiff($val1, $val1, $newSpaceCount);
                $diff[] = "  + $key: $val2";
            } elseif (!is_array($val1) && is_array($val2)) {
                $diff[] = "  - $key: $val1";
                $diff[] = "  + $key: " . getDiff($val2, $val2, $newSpaceCount);
            } else {
                $diff[] = "  - $key: $val1";
                $diff[] = "  + $key: $val2";
            }
        } else {
            if (is_array($val1) && is_array($val2)) {
                $diff[] = "    $key: " . getDiff($val1, $val2, $newSpaceCount);
            } else {
                $diff[] = "    $key: $val1";
            }
        }
    }

    $spaces = str_repeat(' ', $spaceCount);
    $diff = array_map(fn ($item) => $spaces . $item, $diff);

    return '{' . PHP_EOL . implode(PHP_EOL, $diff) . PHP_EOL . $spaces . '}';
}

function getStrValue(string $key, array $json)
{
    $value = $json[$key] ?? null;
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if ($value === null) {
        return 'null';
    }
    return $value;
}
