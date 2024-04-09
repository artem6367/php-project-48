<?php

namespace Differ\Differ\Formaters\Json;

function format(array $data): string
{
    return (string) json_encode($data);
}
