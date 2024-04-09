<?php

namespace Differ\Differ\Formaters\Stylish;

function format(array $data, string $separator = ' ', int $depth = 0): string
{
    $result = [];
    $spaces = str_repeat($separator, $depth * 4 + 2);
    $newDepth = $depth + 1;

    $keys = array_keys($data);
    $result = array_reduce($keys, function ($result, $key) use ($data, $separator, $spaces, $newDepth) {
        $val = $data[$key];
        if (is_array($val)) {
            $result[] = "{$spaces}{$key}: " . format($val, $separator, $newDepth);
        } else {
            $result[] = "{$spaces}{$key}: {$val}";
        }
        return $result;
    }, []);

    return '{' . PHP_EOL . implode(PHP_EOL, $result) . PHP_EOL . str_repeat(' ', $depth * 4) . '}';
}
