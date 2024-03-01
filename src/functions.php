<?php

namespace hexlet\code;

use function hexlet\code\Formaters\format;
use function hexlet\code\Parsers\parseFile;

function gendiff(string $file1, string $file2): string
{
    $json1 = parseFile($file1);
    $json2 = parseFile($file2);

    $diff = getDiff($json1, $json2);

    return format($diff);
}

function getDiff(array $data1, array $data2, $depth = 0): array
{
    $keys = array_unique([...array_keys($data1), ...array_keys($data2)]);
    sort($keys);

    $diff = [];
    $newDepth = $depth + 1;
    foreach ($keys as $key) {
        $val1 = getStrValue($key, $data1);
        $val2 = getStrValue($key, $data2);

        if (array_key_exists($key, $data1) && !array_key_exists($key, $data2)) {
            if (is_array($val1)) {
                $diff["- $key"] = getDiff($val1, $val1, $newDepth);
            } else {
                $diff["- $key"] = $val1;
            }
        } elseif (!array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
            if (is_array($val2)) {
                $diff["+ $key"] = getDiff($val2, $val2, $newDepth);
            } else {
                $diff["+ $key"] = $val2;
            }
        } elseif ($val1 != $val2) {
            if (is_array($val1) && is_array($val2)) {
                $diff["  $key"] = getDiff($val1, $val2, $newDepth);
            } elseif (is_array($val1) && !is_array($val2)) {
                $diff["- $key"] = getDiff($val1, $val1, $newDepth);
                $diff["+ $key"] = $val2;
            } elseif (!is_array($val1) && is_array($val2)) {
                $diff["- $key"] = $val1;
                $diff["+ $key"] = getDiff($val2, $val2, $newDepth);
            } else {
                $diff["- $key"] = $val1;
                $diff["+ $key"] = $val2;
            }
        } else {
            if (is_array($val1) && is_array($val2)) {
                $diff["  $key"] = getDiff($val1, $val2, $newDepth);
            } else {
                $diff["  $key"] = $val1;
            }
        }
    }

    return $diff;
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
