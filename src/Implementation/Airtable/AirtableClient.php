<?php

declare(strict_types=1);

namespace App\Implementation\Airtable;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

class AirtableClient
{
    private HttpClientInterface $httpClient;

    public function __construct(
        HttpClientInterface $httpClient,
        #[Autowire('%airtable.api_key%')]
        string $airtableApiKey,
        #[Autowire('%airtable.base_uri%')]
        string $airtableBaseUri,
    ) {
        Assert::endsWith($airtableBaseUri, '/', 'The base URI must end with a trailing slash.');

        $this->httpClient = $httpClient->withOptions([
            'base_uri' => $airtableBaseUri,
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $airtableApiKey),
            ],
        ]);
    }

    // @phpstan-ignore-next-line
    public function get(string $endpoint): array
    {
        $response = $this->httpClient->request('GET', $endpoint);

        return $response->toArray();
    }
}
