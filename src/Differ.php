<?php

namespace Differ\Differ;

use Differ\Parser;
use Funct\Collection;

const STATUS_NOT_CHANGED = 'not-changed';
const STATUS_CHANGED = 'changed';
const STATUS_REMOVED = 'removed';
const STATUS_ADDED = 'added';

const SIGN_REMOVED = '-';
const SIGN_ADDED = '+';
const SIGN_TAB = ' ';

function genDiff(string $pathToFile1, string $pathToFile2): string {

    $file1Content = Parser\getFileContent($pathToFile1);
    $file2Content = Parser\getFileContent($pathToFile2);
    $data1 = json_decode($file1Content, true, JSON_THROW_ON_ERROR);
    $data2 = json_decode($file2Content, true, JSON_THROW_ON_ERROR);

    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);
    $keys = Collection\union($keys1, $keys2);
    $keys = Collection\sortBy($keys, fn($key) => $key);
    $keys = array_values($keys);

    $diffResult = [];
    foreach($keys as $key) {

        if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {

            if ($data1[$key] === $data2[$key]) {
                $diffResult[] = [
                    'key' => $key,
                    'value' => $data1[$key],
                    'status' => STATUS_NOT_CHANGED
                ];
            } else {
                $diffResult[] = [
                    'key' => $key,
                    'old-value' => $data1[$key],
                    'value' => $data2[$key],
                    'status' => STATUS_CHANGED
                ];
            }
        } else if (array_key_exists($key, $data1)) {
            $diffResult[] = [
                'key' => $key,
                'value' => $data1[$key],
                'status' => STATUS_REMOVED
            ];
        } else if (array_key_exists($key, $data2)) {
            $diffResult[] = [
                'key' => $key,
                'value' => $data2[$key],
                'status' => STATUS_ADDED
            ];
        }
    }

    return formatResult($diffResult);
}

function formatResult (array $diffResult): string {

    $result = "";
    foreach ($diffResult as $item) {

        if (is_bool($item['value'])) {
            $item['value'] = $item['value'] ? "true" : "false";
        }

        switch ($item['status']) {
            case STATUS_NOT_CHANGED:
                $result .= SIGN_TAB . "  {$item['key']}: {$item['value']}\n";
                break;
            case STATUS_CHANGED:
                if (is_bool($item['old-value'])) {
                    $item['old-value'] = $item['old-value'] ? "true" : "false";
                }
                $result .= SIGN_TAB . SIGN_REMOVED . " {$item['key']}: {$item['old-value']}\n";
                $result .= SIGN_TAB . SIGN_ADDED . " {$item['key']}: {$item['value']}\n";
                break;
            case STATUS_ADDED:
                $result .= SIGN_TAB . SIGN_ADDED . " {$item['key']}: {$item['value']}\n";
                break;
            case STATUS_REMOVED:
                $result .= SIGN_TAB . SIGN_REMOVED . " {$item['key']}: {$item['value']}\n";
                break;
        }
    }

    return "{\n{$result}}";
}