<?php

namespace hexlet\code\Formaters\Json;

function format(array $data): string
{
    return (string) json_encode($data);
}
