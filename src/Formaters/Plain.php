<?php

namespace Differ\Differ\Formaters\Plain;

function format(array $data): string
{
    $result = helper($data);
    $keys = array_keys($result);

    $str = array_reduce($keys, function ($acc, $key) use ($result) {
        $value = $result[$key];
        $action = $value['action'];
        $newValue = normalize($value['newValue'] ?? $value['value']);
        $oldValue = normalize($value['oldValue'] ?? '');
        $acc[] = getFormatedString($action, $key, $oldValue, $newValue);
        return $acc;
    }, []);

    return implode(PHP_EOL, $str);
}

function helper(array $data, string $parent = ''): array
{
    $keys = array_keys($data);
    $result = array_reduce($keys, function ($acc, $key) use ($data, $parent) {
        $value = $data[$key];
        $reportValue = is_array($value) ? '[complex value]' : $value;
        $newParent = (!empty($parent) ? "{$parent}." : '') . mb_substr($key, 2);
        if (mb_strpos($key, '- ') === 0) {
            $acc[$newParent] = ['action' => 'removed', 'value' => $reportValue];
        } elseif (mb_strpos($key, '+ ') === 0) {
            if (array_key_exists($newParent, $acc)) {
                $acc[$newParent] = [
                    'action' => 'updated',
                    'oldValue' => $acc[$newParent]['value'],
                    'newValue' => $reportValue,
                ];
            } else {
                $acc[$newParent] = ['action' => 'added', 'value' => $reportValue];
            }
        }
        if (is_array($value)) {
            $acc = array_merge($acc, helper($value, $newParent));
        }
        return $acc;
    }, []);

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

function getFormatedString(string $action, string $key, string $oldValue, string $newValue): string
{
    switch ($action) {
        case 'removed':
            return "Property '$key' was removed";
        case 'updated':
            return "Property '$key' was updated. From $oldValue to $newValue";
        case 'added':
            return "Property '$key' was added with value: $newValue";
        default:
            return '';
    }
}
