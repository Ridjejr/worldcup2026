<?php

namespace App\Tests\Service;

use App\Service\FootballApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FootballApiServiceTest extends TestCase
{
    public function testGetFixturesReturnsData(): void
    {
        // 1. On prépare une fausse réponse JSON
        $mockData = ['response' => [['fixture' => ['id' => 123]]]];
        
        // 2. On mocke le ResponseInterface de Symfony
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn($mockData);
        $response->method('getStatusCode')->willReturn(200);

        // 3. On mocke le HttpClient
        $client = $this->createMock(HttpClientInterface::class);
        $client->method('request')->willReturn($response);

        // 4. On instancie le service avec les mocks
        $service = new FootballApiService($client, 'fake_key');

        // 5. On exécute la méthode
        $result = $service->getFixtures(39, 2022);

        // 6. Assertions
        $this->assertIsArray($result);
        $this->assertArrayHasKey('response', $result);
        $this->assertEquals(123, $result['response'][0]['fixture']['id']);
    }
}