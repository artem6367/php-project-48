<?php

namespace Differ\Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $file): array
{
    if (str_ends_with($file, '.json')) {
        return parseJsonFile($file);
    }

    if (str_ends_with($file, '.yaml') || str_ends_with($file, '.yml')) {
        return parseYamlFile($file);
    }

    throw new \Exception('gendiff поддерживает только файлы json и yaml');
}

function parseJsonFile(string $file): array
{
    $content = getFileContent($file);
    return json_decode($content, true);
}

function parseYamlFile(string $file): array
{
    $content = getFileContent($file);
    return Yaml::parse($content);
}

function getFileContent(string $file): string
{
    $filepath = realpath($file);

    if ($filepath === false) {
        throw new \Exception($file . ' файл не найден');
    }

    $content = file_get_contents($filepath);

    if ($content === false) {
        throw new \Exception($filepath . ' не удалось прочитать файл');
    }

    return $content;
}
