<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

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
