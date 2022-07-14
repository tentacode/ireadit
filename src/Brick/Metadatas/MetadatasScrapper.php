<?php

declare(strict_types=1);

namespace App\Brick\Metadatas;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MetadatasScrapper
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    /**
     * @return array<string,string>
     */
    public function scrapMetadatas(string $url): array
    {
        $response = $this->httpClient->request('GET', $url, [
            'timeout' => 10,
        ]);

        if (isset($response->getHeaders()['content-type'][0]) && strpos(
            $response->getHeaders()['content-type'][0],
            'image/'
        ) !== false) {
            return [
                'og:image' => $url,
            ];
        }

        $crawler = new Crawler($response->getContent());

        $metadatas = [];

        foreach ($crawler->filter('head > title') as $title) {
            $metadatas['title'] = $title->nodeValue ?? '';
        }

        foreach ($crawler->filter('head > meta') as $meta) {
            $meta = new Crawler($meta);
            $name = $meta->attr('name') ?? $meta->attr('property');
            $content = $meta->attr('content') ?? '';
            $metadatas[$name] = $content;
        }

        return $metadatas;
    }
}
