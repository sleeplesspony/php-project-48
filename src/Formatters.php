<?php

namespace Differ\Formatters;

use Differ\Formatters\Stylish;
use Differ\Formatters\Plain;

function formatResult(array $diffTree, string $format): string
{
    switch ($format) {
        case "stylish":
            return Stylish\stylish($diffTree);
        break;
        case "plain":
            return Plain\plain($diffTree);
        break;
        case "json":
            return Json\json($diffTree);
        break;
    }
}
