<?php

namespace Differ\Differ;

use Exception;

use function Differ\Differ\Formaters\Stylish\format as stylish;
use function Differ\Differ\Formaters\Plain\format as plain;
use function Differ\Differ\Formaters\Json\format as json;

function format(array $data, string $formatName = 'stylish'): string
{
    switch ($formatName) {
        case 'stylish':
            return stylish($data);
        case 'plain':
            return plain($data);
        case 'json':
            return json($data);
        default:
            throw new Exception('Unknown format ' . $formatName);
    }
}
