<?php

namespace Differ\Differ\Formaters\Plain;

function format(array $data): string
{
    $result = helper($data);
    $str = [];
    foreach ($result as $key => $value) {
        $action = $value['action'];
        $newValue = $value['newValue'] ?? $value['value'];
        $newValue = normalize($newValue);
        $oldValue = normalize($value['oldValue'] ?? '');
        if ($action == 'removed') {
            $str[] = "Property '$key' was removed";
        } elseif ($action == 'updated') {
            $str[] = "Property '$key' was updated. From $oldValue to $newValue";
        } else {
            $str[] = "Property '$key' was added with value: $newValue";
        }
    }

    return implode(PHP_EOL, $str);
}

function helper(array $data, string $parent = ''): array
{
    $result = [];

    foreach ($data as $key => $value) {
        $reportValue = is_array($value) ? '[complex value]' : $value;
        $newParent = ($parent ? $parent . '.' : '') . mb_substr($key, 2);
        if (mb_strpos($key, '- ') === 0) {
            $result[$newParent] = ['action' => 'removed', 'value' => $reportValue];
        } elseif (mb_strpos($key, '+ ') === 0) {
            if (array_key_exists($newParent, $result)) {
                $result[$newParent] = [
                    'action' => 'updated',
                    'oldValue' => $result[$newParent]['value'],
                    'newValue' => $reportValue,
                ];
            } else {
                $result[$newParent] = ['action' => 'added', 'value' => $reportValue];
            }
        }
        if (is_array($value)) {
            $result = array_merge($result, helper($value, $newParent));
        }
    }

    return $result;
}

function normalize(string $value): string
{
    if ($value == '') {
        return "''";
    }
    if (!in_array($value, ['true', 'false', 'null', '[complex value]', '0'], true)) {
        return "'$value'";
    }

    return $value;
}
