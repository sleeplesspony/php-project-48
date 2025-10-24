<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $format, string $dataString): array
{

    switch ($format) {
        case 'json':
            return json_decode($dataString, true, JSON_THROW_ON_ERROR);
        case 'yaml':
        case 'yml':
            return Yaml::parse($dataString);
        default:
            throw new \UnexpectedValueException("Unsupported file format: {$format}");
    }
}
