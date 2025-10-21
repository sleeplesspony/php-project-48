<?php

namespace Differ\Differ;

use Differ\Parser;
use Differ\Formatters;
use Funct\Collection;

const STATUS_NOT_CHANGED = 'not-changed';
const STATUS_CHANGED = 'changed';
const STATUS_REMOVED = 'removed';
const STATUS_ADDED = 'added';
const STATUS_PARENT = 'parent';

function genDiff(string $pathToFile1, string $pathToFile2, $format = "stylish"): string
{
    $file1Content = Parser\getFileContent($pathToFile1);
    $file2Content = Parser\getFileContent($pathToFile2);

    $data1 = Parser\parse(pathinfo($pathToFile1)["extension"], $file1Content);
    $data2 = Parser\parse(pathinfo($pathToFile2)["extension"], $file2Content);

    return Formatters\formatResult(getDiffTree($data1, $data2), $format);
}

function getDiffTree(array $data1, array $data2): array
{
    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);
    $keys = Collection\union($keys1, $keys2);
    $keys = Collection\sortBy($keys, fn($key) => $key);
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
