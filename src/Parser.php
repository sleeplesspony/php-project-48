<?php

namespace Gendiff\Parser;

function printFileContent($filePath) {

    $absolutePath = realpath($filePath);
    if ($absolutePath === false) {

        print_r("File not found: {$filePath}");

    } else {

        $fileName = basename($absolutePath);

        $fileContent = file_get_contents($absolutePath);
        $data = json_decode($fileContent);
        print_r($fileName . "\n\n");
        foreach($data as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? "true" : "false";
            }
            print_r("{$key} : {$value}\n");
        }

    }

    print_r("\n\n");
}