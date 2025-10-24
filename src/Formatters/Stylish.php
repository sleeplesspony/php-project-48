<?php

namespace Differ\Formatters\Stylish;

use Differ\Differ;

const SIGN_REMOVED = '- ';
const SIGN_ADDED = '+ ';
const REPLACER = " ";
const REPLACER_COUNT = 4;

function stylish(array $diffTree): string
{
    $stylishIter = function (array $diffTree, int $depth) use (&$stylishIter): string {
        $result = [];

        $indentFull = str_repeat(REPLACER, REPLACER_COUNT * $depth);
        $indentSign = str_repeat(REPLACER, REPLACER_COUNT * $depth - 2);
        $indentPrev = str_repeat(REPLACER, REPLACER_COUNT * ($depth - 1));

        foreach ($diffTree as $item) {
            switch ($item['status']) {
                case Differ\STATUS_PARENT:
                    $childrenStr = $stylishIter($item['children'], $depth + 1);
                    $result[] = "{$indentFull}{$item['key']}: {$childrenStr}";
                    break;

                case Differ\STATUS_NOT_CHANGED:
                    $valueStr = toString($item['value'], $depth);
                    $result[] = "{$indentFull}{$item['key']}: {$valueStr}";
                    break;

                case Differ\STATUS_CHANGED:
                    $oldValueStr = toString($item['old-value'], $depth);
                    $result[] = sprintf("%s%s%s: %s", $indentSign, SIGN_REMOVED, $item['key'], $oldValueStr);

                    $newValueStr = toString($item['value'], $depth);
                    $result[] = sprintf("%s%s%s: %s", $indentSign, SIGN_ADDED, $item['key'], $newValueStr);
                    break;

                case Differ\STATUS_ADDED:
                    $valueStr = toString($item['value'], $depth);
                    $result[] = sprintf("%s%s%s: %s", $indentSign, SIGN_ADDED, $item['key'], $valueStr);
                    break;

                case Differ\STATUS_REMOVED:
                    $valueStr = toString($item['value'], $depth);
                    $result[] = sprintf("%s%s%s: %s", $indentSign, SIGN_REMOVED, $item['key'], $valueStr);
                    break;

                default:
                    throw new \UnexpectedValueException("Unknown status: " . $item['status']);
            }
        }

        $resultStr = implode("\n", $result);

        if ($depth === 1) {
            return "{\n{$resultStr}\n}";
        }

        return "{\n{$resultStr}\n{$indentPrev}}";
    };

    return $stylishIter($diffTree, 1);
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
    $resultStr = implode("\n", $result);
    return "{\n{$resultStr}\n{$indentPrev}}";
}
