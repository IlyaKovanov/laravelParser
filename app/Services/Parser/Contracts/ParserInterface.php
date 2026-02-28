<?php

namespace App\Services\Parser\Contracts;

interface ParserInterface
{
    public function parse(array $config): array;
}
