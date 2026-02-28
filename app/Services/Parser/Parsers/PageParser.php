<?php

namespace App\Services\Parser\Parsers;

use App\Services\Parser\Contracts\ParserInterface;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\UriResolver;


class PageParser implements ParserInterface
{
    public function parse(array $config): array
    {
        $url = $config['url'];
        $selectors = $config['selectors'];
        $selectors_type = $config['selectors_type'];

        $html = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ])->get($url)->body();

        $crawler = new Crawler($html);

        $results = [];

        foreach ($selectors as $key => $selector) {
            $selector_type = $selectors_type[$key] ?? null;
            $results[$key] = match ($selector_type) {
                'single' => $this->extractSingle($crawler, $selector),
                'multiple' => $this->extractMultiple($crawler, $selector),
                'images' => $this->extractImages($crawler, $selector),
                default => null,
            };
        }

        return $results;
    }

    protected function extractSingle(Crawler $crawler, string $selector): string|null
    {
        if(!$this->checkNode($crawler, $selector)) {
            return null;
        }

        return $crawler->filter($selector)->text();
    }

    protected function extractMultiple(Crawler $crawler, string $selector): array|null
    {
        if(!$this->checkNode($crawler, $selector)) {
            return null;
        }

        return $crawler->filter($selector)->each(fn($node) => $node->text());
    }

    protected function extractImages(Crawler $crawler, string $selector): array|null
    {

        if(!$this->checkNode($crawler, $selector)) {
            return null;
        }

        return $crawler->filter($selector)->each(function($node) {
            // Преобразуем относительные пути в абсолютные
            $src = $node->attr('src');
            if (!str_starts_with($src, 'http')) {
                $src = UriResolver::resolve($src, 'https://rudingli.ru/');
            }
            return [
                'src' => $src,
                'alt' => $node->attr('alt'),
            ];
        });

    }

    private function checkNode(Crawler $crawler, string $selector):bool
    {
        $node = $crawler->filter($selector);
        if($node->count()) {
            return true;
        }

        return false;
    }

}
