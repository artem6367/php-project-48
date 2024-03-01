<?php

namespace hexlet\code;

use Exception;

use function hexlet\code\Formaters\Stylish\format as stylish;
use function hexlet\code\Formaters\Plain\format as plain;

function format(array $data, string $formatName = 'stylish'): string
{
    switch ($formatName) {
        case 'stylish':
            return stylish($data);
        case 'plain':
            return plain($data);
        default:
            throw new Exception('Unknown format ' . $formatName);
    }
}
