<?php

namespace Differ\Parser;
use Funct\Collection;

function getFileContent(string $filePath): string {

    $absolutePath = realpath($filePath);
    if ($absolutePath === false) {
        throw new \Exception("File not found: {$filePath}");
    }

    $fileContent = file_get_contents($absolutePath);
    if ($fileContent === false) {
        throw new \Exception("Can not read file: {$filePath}");
    }

    return $fileContent;

}

function printFileContent(string $filePath): void {

    $fileContent = getFileContent($filePath);
    $fileName = basename($filePath);
    $data = json_decode($fileContent);
    print_r($fileName . "\n\n");
    foreach($data as $key => $value) {
        if (is_bool($value)) {
            $value = $value ? "true" : "false";
        }
        print_r("{$key} : {$value}\n");
    }

    print_r("\n\n");
}