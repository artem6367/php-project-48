<?php

namespace Differ\Differ\Formaters\Plain;

function format(array $data): string
{
    $result = helper($data);
    $keys = array_keys($result);

    $str = array_reduce($keys, function ($str, $key) use ($result) {
        $value = $result[$key];
        $action = $value['action'];
        $newValue = normalize($value['newValue'] ?? $value['value']);
        $oldValue = normalize($value['oldValue'] ?? '');
        if ($action === 'removed') {
            $str[] = "Property '$key' was removed";
        } elseif ($action === 'updated') {
            $str[] = "Property '$key' was updated. From $oldValue to $newValue";
        } else {
            $str[] = "Property '$key' was added with value: $newValue";
        }
        return $str;
    }, []);

    return implode(PHP_EOL, $str);
}

function helper(array $data, string $parent = ''): array
{
    $keys = array_keys($data);
    $result = array_reduce($keys, function ($result, $key) use ($data, $parent) {
        $value = $data[$key];
        $reportValue = is_array($value) ? '[complex value]' : $value;
        $newParent = ($parent ? "{$parent}." : '') . mb_substr($key, 2);
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
        return $result;
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
