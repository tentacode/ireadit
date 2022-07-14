<?php

declare(strict_types=1);

namespace spec\App\Brick\Metadatas;

use App\Brick\Metadatas\MetadatasScrapper;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MetadatasScrapperSpec extends ObjectBehavior
{
    public function let(HttpClientInterface $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MetadatasScrapper::class);
    }
}
