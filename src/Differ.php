<?php

namespace Differ\Differ;

use Differ\Parser;
use Funct\Collection;
use Symfony\Component\Yaml\Yaml;

const STATUS_NOT_CHANGED = 'not-changed';
const STATUS_CHANGED = 'changed';
const STATUS_REMOVED = 'removed';
const STATUS_ADDED = 'added';
const STATUS_PARENT = 'parent';

const SIGN_REMOVED = '- ';
const SIGN_ADDED = '+ ';
//const SIGN_NOT_CHANGED = '  ';
//const SIGN_PARENT = '  ';
const REPLACER = " ";
const REPLACER_COUNT = 4;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $file1Content = Parser\getFileContent($pathToFile1);
    $file2Content = Parser\getFileContent($pathToFile2);

    $data1 = Parser\parse(pathinfo($pathToFile1)["extension"], $file1Content);
    $data2 = Parser\parse(pathinfo($pathToFile2)["extension"], $file2Content);

    return formatResult(getDiffTree($data1, $data2));
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

function toString(mixed $item, int $depth): string
{
    if (is_null($item)) {
        return "null";
    }

    if (is_bool($item)) {
        return $item ? "true" : "false";
    }

    if (!is_array($item)) {
        return (string)$item;
    }

    $indent = str_repeat(REPLACER, REPLACER_COUNT * ($depth + 1));
    $result = [];

    foreach ($item as $key => $value) {
        $valueStr = toString($value, $depth + 1);
        $result[] = "{$indent}{$key}: {$valueStr}";
    }

    $indentPrev = str_repeat(REPLACER, REPLACER_COUNT * $depth);
    return "{\n" . implode("\n", $result) . "\n{$indentPrev}}";
}

function stylish_iter($diffTree, $depth)
{
    $result = [];

    // Отступ для текущего уровня
    $indentFull = str_repeat(REPLACER, REPLACER_COUNT * $depth);

    // Отступ для строк со знаком
    $indentSign = str_repeat(REPLACER, REPLACER_COUNT * $depth - 2);

    // Отступ для закрывающей скобки (на уровень выше)
    $indentPrev = str_repeat(REPLACER, REPLACER_COUNT * ($depth - 1));

    foreach ($diffTree as $item) {
        switch ($item['status']) {
            case STATUS_PARENT:
                $childrenStr = stylish_iter($item['children'], $depth + 1);
                $result[] = "{$indentFull}{$item['key']}: {$childrenStr}";
                break;

            case STATUS_NOT_CHANGED:
                $valueStr = toString($item['value'], $depth);
                $result[] = "{$indentFull}{$item['key']}: {$valueStr}";
                break;

            case STATUS_CHANGED:
                $oldValueStr = toString($item['old-value'], $depth);
                $result[] = "{$indentSign}" . SIGN_REMOVED . "{$item['key']}: {$oldValueStr}";

                $newValueStr = toString($item['value'], $depth);
                $result[] = "{$indentSign}" . SIGN_ADDED . "{$item['key']}: {$newValueStr}";
                break;

            case STATUS_ADDED:
                $valueStr = toString($item['value'], $depth);
                $result[] = "{$indentSign}" . SIGN_ADDED . "{$item['key']}: {$valueStr}";
                break;

            case STATUS_REMOVED:
                $valueStr = toString($item['value'], $depth);
                $result[] = "{$indentSign}" . SIGN_REMOVED . "{$item['key']}: {$valueStr}";
                break;
        }
    }

    if ($depth === 1) {
        return "{\n" . implode("\n", $result) . "\n}";
    }

    return "{\n" . implode("\n", $result) . "\n" . $indentPrev . "}";
}

function formatResult(array $diffTree, $format = "stylish"): string
{
    return stylish_iter($diffTree, 1);
}
