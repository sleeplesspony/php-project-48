<?php

namespace Differ\Formatters\Json;

function json(array $diffTree): string
{
    return json_encode($diffTree, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
}
