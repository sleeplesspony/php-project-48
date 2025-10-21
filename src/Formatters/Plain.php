<?php

namespace Differ\Formatters\Plain;

use Differ\Differ;

function plain(array $diffTree): string
{
    $plainIter = function (array $diffTree, string $path) use (&$plainIter) {
        $result = [];

        foreach ($diffTree as $item) {
            $pathPrev = $path;
            $path = $path ? ($path . "." . $item["key"]) : $item["key"];

            switch ($item['status']) {
                case Differ\STATUS_PARENT:
                    $result[] = $plainIter($item['children'], $path);
                    break;

                case Differ\STATUS_NOT_CHANGED:
                    break;

                case Differ\STATUS_CHANGED:
                    $oldValueStr = toString($item['old-value']);
                    $newValueStr = toString($item['value']);
                    $result[] = "Property '{$path}' was updated. From {$oldValueStr} to {$newValueStr}";
                    break;

                case Differ\STATUS_ADDED:
                    $newValueStr = toString($item['value']);
                    $result[] = "Property '{$path}' was added with value: {$newValueStr}";
                    break;

                case Differ\STATUS_REMOVED:
                    $result[] = "Property '{$path}' was removed";
                    break;
            }

            $path = $pathPrev;
        }

        return implode("\n", $result);
    };

    return $plainIter($diffTree, "");
}

function toString(mixed $item): string
{

    if (is_null($item)) {
        return "null";
    }

    if (is_bool($item)) {
        return $item ? "true" : "false";
    }

    if (is_array($item)) {
        return "[complex value]";
    }

    return "'" . (string)$item . "'";
}
