<?php

namespace hexlet\code;

use Exception;

use function hexlet\code\Formaters\Stylish\format as stylish;
use function hexlet\code\Formaters\Plain\format as plain;
use function hexlet\code\Formaters\Json\format as json;

function format(array $data, string $formatName = 'stylish'): string
{
    switch ($formatName) {
        case 'stylish':
            return stylish($data);
        case 'plain':
            return plain($data);
        case 'json';
            return json($data);
        default:
            throw new Exception('Unknown format ' . $formatName);
    }
}
