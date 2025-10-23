<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

const SUPPORTED_EXTENSIONS = [ 'json', 'yaml', 'yml'];

function parse(string $format, string $dataString): array
{
    if ($format === "json") {
        return parseJson($dataString);
    } elseif ($format === "yaml" || $format === "yml") {
        return parseYaml($dataString);
    }

    throw new \UnexpectedValueException("Unsupported file format: {$format}");
}

function parseJson(string $dataString): array
{
    return json_decode($dataString, true, JSON_THROW_ON_ERROR);
}

function parseYaml(string $dataString): array
{
    return Yaml::parse($dataString);
}

function getFileData(string $pathToFile): array
{
    $fileContent = getFileContent($pathToFile);
    $extension = getFileExtension($pathToFile);
    return parse($extension, $fileContent);
}

function getFileContent(string $pathToFile): string
{

    $absolutePath = realpath($pathToFile);
    if ($absolutePath === false) {
        throw new \UnexpectedValueException("File not found: {$pathToFile}");
    }

    $fileContent = file_get_contents($absolutePath);
    if ($fileContent === false) {
        throw new \UnexpectedValueException("Can not read file: {$pathToFile}");
    }

    return $fileContent;
}

function getFileExtension(string $pathToFile): string
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    if (in_array($extension, SUPPORTED_EXTENSIONS, true)) {
        return $extension;
    } else {
        throw new \UnexpectedValueException("Unsupported file extension: {$extension}");
    }
}

function printFileContent(string $pathToFile): void
{

    $fileContent = getFileContent($pathToFile);
    $fileName = basename($pathToFile);
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
