<?php

declare(strict_types=1);

namespace spec\App\Implementation\Airtable;

use App\Implementation\Airtable\AirtableClient;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AirtableClientSpec extends ObjectBehavior
{
    public function let(HttpClientInterface $httpClient)
    {
        $httpClient->withOptions([
            'base_uri' => 'https://api.airtable.com/v0/',
            'headers' => [
                'Authorization' => 'Bearer ABCDEF123456',
            ],
        ])->shouldBeCalled()
            ->willReturn($httpClient);

        $this->beConstructedWith($httpClient, 'ABCDEF123456', 'https://api.airtable.com/v0/',);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AirtableClient::class);
    }

    public function it_can_get_endpoint_as_an_array($httpClient, ResponseInterface $response)
    {
        $links = [
            [
                'id' => '123',
                'fields' => [
                    'title' => 'My link',
                    'url' => 'https://tentacode.test',
                ],
            ],
        ];

        $response->toArray()
            ->willReturn($links);

        $httpClient->request('GET', 'tables')
            ->shouldBeCalled()
            ->willReturn($response);

        $this->get('tables')
            ->shouldReturn($links);
    }
}
