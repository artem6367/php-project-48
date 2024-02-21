<?php

namespace hexlet\code;

function gendiff(string $file1, string $file2): string
{
    $file1 = realpath($file1);
    $file2 = realpath($file2);

    if ($file1 === false || $file2 === false) {
        return 'Файлы не найдены';
    }

    $content1 = file_get_contents($file1);
    $content2 = file_get_contents($file2);

    if ($content1 === false || $content2 === false) {
        return 'Не удалось считать файлы';
    }

    $json1 = json_decode($content1, true);
    $json2 = json_decode($content2, true);

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
