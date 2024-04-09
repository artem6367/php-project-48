<?php

namespace Differ\Differ\Formaters\Stylish;

function format(array $data, string $separator = ' ', int $depth = 0): string
{
    $spaces = str_repeat($separator, $depth * 4 + 2);
    $newDepth = $depth + 1;

    $keys = array_keys($data);
    $result = array_reduce($keys, function ($result, $key) use ($data, $separator, $spaces, $newDepth) {
        $val = $data[$key];
        if (is_array($val)) {
            return array_merge($result, ["{$spaces}{$key}: " . format($val, $separator, $newDepth)]);
        } else {
            return array_merge($result, ["{$spaces}{$key}: {$val}"]);
        }
    }, []);

    return '{' . PHP_EOL . implode(PHP_EOL, $result) . PHP_EOL . str_repeat(' ', $depth * 4) . '}';
}
