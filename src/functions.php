<?php

namespace Differ\Differ;

use function Differ\Differ\format;
use function Differ\Differ\Parsers\parseFile;

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

    $result = array_reduce($keys, function ($diff, $key) use ($data1, $data2) {
        $val1 = $data1[$key] ?? null;
        $val1 = getValueString($val1);

        $val2 = $data2[$key] ?? null;
        $val2 = getValueString($val2);

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
        } elseif ($val1 !== $val2) {
            if (is_array($val1) && is_array($val2)) {
                $diff["  $key"] = getDiff($val1, $val2);
            } elseif (is_array($val1)) {
                $diff["- $key"] = getDiff($val1, $val1);
                $diff["+ $key"] = $val2;
            } elseif (is_array($val2)) {
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
        return $diff;
    }, []);

    return $result;
}

/**
 * @param mixed $val
 * @return mixed
 */
function getValueString($val)
{
    if (is_bool($val)) {
        return $val ? 'true' : 'false';
    }

    if ($val === null) {
        return 'null';
    }

    return $val;
}
