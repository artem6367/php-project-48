<?php

namespace hexlet\code;

function gendiff($file1, $file2)
{
    if (mb_strpos($file1, '/') !== 0) {
        $file1 = getcwd() . '/' . $file1;
    }

    if (mb_strpos($file2, '/') !== 0) {
        $file2 = getcwd() . '/' . $file2;
    }

    $json1 = json_decode(file_get_contents($file1), true);
    $json2 = json_decode(file_get_contents($file2), true);

    $keys = array_unique([...array_keys($json1), ...array_keys($json2)]);
    sort($keys);

    $diff = [];
    foreach ($keys as $key) {
        $val1 = $json1[$key] ?? null;
        $val2 = $json2[$key] ?? null;
        if (is_bool($val1)) {
            $val1 = $val1 ? 'true' : 'false';
        }
        if (is_bool($val2)) {
            $val2 = $val2 ? 'true' : 'false';
        }
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
