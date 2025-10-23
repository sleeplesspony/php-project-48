<?php

namespace Differ\Differ;

use function Differ\Parser\getFileData;
use function Differ\Formatters\formatResult;
use function Funct\Collection\union;
use function Funct\Collection\sortBy;

const STATUS_NOT_CHANGED = 'not-changed';
const STATUS_CHANGED = 'changed';
const STATUS_REMOVED = 'removed';
const STATUS_ADDED = 'added';
const STATUS_PARENT = 'parent';

function genDiff(string $pathToFile1, string $pathToFile2, string $format = "stylish"): string
{
    $data1 = getFileData($pathToFile1);
    $data2 = getFileData($pathToFile2);
    return formatResult(getDiffTree($data1, $data2), $format);
}

function getDiffTree(array $data1, array $data2): array
{
    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);
    $keys = union($keys1, $keys2);
    $keys = sortBy($keys, fn($key) => $key);
    $keys = array_values($keys);

    $diffTree = [];

    foreach ($keys as $key) {
        if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) { // ключи есть в обоих массивах
            if ($data1[$key] === $data2[$key]) {
                $diffTree[] = [
                    'key' => $key,
                    'value' => $data1[$key],
                    'status' => STATUS_NOT_CHANGED
                ];
            } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
                $diffTree[] = [
                    'key' => $key,
                    'status' => STATUS_PARENT,
                    'children' => getDiffTree($data1[$key], $data2[$key])
                ];
            } else {
                $diffTree[] = [
                    'key' => $key,
                    'old-value' => $data1[$key],
                    'value' => $data2[$key],
                    'status' => STATUS_CHANGED
                ];
            }
        } elseif (array_key_exists($key, $data1)) { // ключ только в первом массиве
            $diffTree[] = [
                'key' => $key,
                'value' => $data1[$key],
                'status' => STATUS_REMOVED
            ];
        } elseif (array_key_exists($key, $data2)) { // ключ только во втором массиве
            $diffTree[] = [
                'key' => $key,
                'value' => $data2[$key],
                'status' => STATUS_ADDED
            ];
        }
    }

    return $diffTree;
}
