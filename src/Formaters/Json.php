<?php

namespace hexlet\code\Formaters\Json;

function format(array $data): string
{
    return json_encode($data);
}
