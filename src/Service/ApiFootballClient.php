<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiFootballClient
{
    public function __construct(
        private HttpClientInterface $http,
        private string $baseUrl,
        private string $apiKey
    ) {}

    private function request(string $path, array $query = []): array
    {
        $res = $this->http->request('GET', rtrim($this->baseUrl, '/') . $path, [
            'query' => $query,
            'headers' => [
                'x-apisports-key' => $this->apiKey,
            ],
        ]);

        return $res->toArray(false);
    }

    public function getLeagues(string $search = ''): array
    {
        return $this->request('/leagues', $search ? ['search' => $search] : []);
    }

    public function getFixtures(int $league, int $season): array
    {
        return $this->request('/fixtures', [
            'league' => $league,
            'season' => $season,
        ]);
    }

    public function getStandings(int $league, int $season): array
    {
        return $this->request('/standings', [
            'league' => $league,
            'season' => $season,
        ]);
    }
}
