<?php

namespace App\Tests\Api;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WhoamiTest extends WebTestCase
{
    use PHPMatcherAssertions;

    public function test_it_should_return_an_error_if_not_logged_in(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/whoami');

        $response = $client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
        
        $this->assertMatchesPattern(
            [
                'type' => '@string@',
                'detail' => 'Full authentication is required to access this resource.',
                'status' => 401,
                'title' => 'An error occurred',
                'class' => 'Symfony\Component\HttpKernel\Exception\HttpException',
                'trace' => ['@...@'],
            ],
            json_decode($response->getContent(), true)
        );
    }

    public function test_it_retrieve_user_data_if_logged_in(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('gabriel@tentacode.test');

        $client->loginUser($testUser);

        $client->request('GET', '/api/whoami');
        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        
        $this->assertMatchesPattern(
            [
                'uuid' => '@uuid@',
                'username' => 'tentacode',
                'email' => 'gabriel@tentacode.test',
            ],
            json_decode($response->getContent(), true)
        );
    }
}
