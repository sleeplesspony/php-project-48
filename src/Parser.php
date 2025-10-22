<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parseJson(string $dataString): array
{
    return json_decode($dataString, true, JSON_THROW_ON_ERROR);
}

function parseYaml(string $dataString): array
{
    return Yaml::parse($dataString);
}

function parse(string $format, string $dataString): array
{

    $parse = "";
    if ($format === "json") {
        $parse =  __NAMESPACE__ . "\parseJson";
    } elseif ($format === "yaml" || $format === "yml") {
        $parse =  __NAMESPACE__ . "\parseYaml";
    }

    return $parse($dataString);
}

function getFileContent(string $filePath): string
{

    $absolutePath = realpath($filePath);
    if ($absolutePath === false) {
        throw new \UnexpectedValueException("File not found: {$filePath}");
    }

    $fileContent = file_get_contents($absolutePath);
    if ($fileContent === false) {
        throw new \UnexpectedValueException("Can not read file: {$filePath}");
    }

    return $fileContent;
}

function printFileContent(string $filePath): void
{

    $fileContent = getFileContent($filePath);
    $fileName = basename($filePath);
    $data = json_decode($fileContent);
    print_r($fileName . "\n\n");
    foreach ($data as $key => $value) {
        if (is_bool($value)) {
            $value = $value ? "true" : "false";
        }
        print_r("{$key} : {$value}\n");
    }

    print_r("\n\n");
}
