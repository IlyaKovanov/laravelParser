<?php

namespace App\Services\Parser;

use App\Services\Parser\Parsers\PageParser;

class ParserService
{
    public function __construct(
        protected PageParser $pageParser,
        // позже добавятся другие парсеры
    ) {}

    public function parse(array $config): array
    {
        return match($config['type']) {
            'single' => $this->pageParser->parse($config),
            //'list'   => $this->listParser->parse($config['urls'], $config['selectors']),
            //'catalog' => $this->catalogParser->parse($config['catalogUrl'], $config),
        };
    }
}
