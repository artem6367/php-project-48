<?php

namespace hexlet\code;

use function hexlet\code\format;
use function hexlet\code\Parsers\parseFile;

function gendiff(string $file1, string $file2, string $formatName = 'stylish'): string
{
    $json1 = parseFile($file1);
    $json2 = parseFile($file2);

    $diff = getDiff($json1, $json2);

    return format($diff, $formatName);
}

function getDiff(array $data1, array $data2): array
{
    $keys = array_unique([...array_keys($data1), ...array_keys($data2)]);
    sort($keys);

    $diff = [];

    foreach ($keys as $key) {
        $val1 = $data1[$key] ?? null;
        if (is_bool($val1)) {
            $val1 = $val1 ? 'true' : 'false';
        }
        if ($val1 === null) {
            $val1 = 'null';
        }

        $val2 = $data2[$key] ?? null;
        if (is_bool($val2)) {
            $val2 = $val2 ? 'true' : 'false';
        }
        if ($val2 === null) {
            $val2 = 'null';
        }

        if (array_key_exists($key, $data1) && !array_key_exists($key, $data2)) {
            if (is_array($val1)) {
                $diff["- $key"] = getDiff($val1, $val1);
            } else {
                $diff["- $key"] = $val1;
            }
        } elseif (!array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
            if (is_array($val2)) {
                $diff["+ $key"] = getDiff($val2, $val2);
            } else {
                $diff["+ $key"] = $val2;
            }
        } elseif ($val1 != $val2) {
            if (is_array($val1) && is_array($val2)) {
                $diff["  $key"] = getDiff($val1, $val2);
            } elseif (is_array($val1) && !is_array($val2)) {
                $diff["- $key"] = getDiff($val1, $val1);
                $diff["+ $key"] = $val2;
            } elseif (!is_array($val1) && is_array($val2)) {
                $diff["- $key"] = $val1;
                $diff["+ $key"] = getDiff($val2, $val2);
            } else {
                $diff["- $key"] = $val1;
                $diff["+ $key"] = $val2;
            }
        } else {
            if (is_array($val1) && is_array($val2)) {
                $diff["  $key"] = getDiff($val1, $val2);
            } else {
                $diff["  $key"] = $val1;
            }
        }
    }

    return $diff;
}
