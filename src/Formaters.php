<?php

namespace hexlet\code\Formaters;

function format(array $data, $separator = ' ', $depth = 0)
{
    $result = [];
    $spaces = str_repeat($separator, $depth * 4 + 2);
    $newDepth = $depth + 1;

    foreach ($data as $key => $val) {
        if (is_array($val)) {
            $result[] = "{$spaces}{$key}: " . format($val, $separator, $newDepth);
        } else {
            $result[] = "{$spaces}{$key}: {$val}";
        }
    }

    return '{' . PHP_EOL . implode(PHP_EOL, $result) . PHP_EOL . str_repeat(' ', $depth * 4) . '}';
}
