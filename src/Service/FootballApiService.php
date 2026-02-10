<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FootballApiService
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $rapidApiKey)
    {
        $this->client = $client;
        $this->apiKey = $rapidApiKey;
    }

    // Méthode existante pour le détail
    public function getMatchDetails(int $fixtureId): ?array
    {
        // ... (code précédent)
        return $this->get('/fixtures', ['id' => $fixtureId]);
    }

    // NOUVELLE MÉTHODE pour l'import
    public function getFixtures(int $leagueId, int $season): array
    {
        return $this->get('/fixtures', [
            'league' => $leagueId,
            'season' => $season
        ]);
    }

    // Méthode privée pour éviter la duplication de code
    private function get(string $endpoint, array $query): ?array
    {
        try {
            $response = $this->client->request('GET', 'https://v3.football.api-sports.io' . $endpoint, [
                'headers' => [
                    'x-apisports-key' => $this->apiKey,
                ],
                'query' => $query
            ]);

            // --- DEBUT DEBUG ---
            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                echo "\n🔴 ERREUR HTTP : " . $statusCode . "\n";
                echo "Réponse API : " . $response->getContent(false) . "\n";
                return [];
            }
            // --- FIN DEBUG ---

            $data = $response->toArray();
            
            // --- DEBUG SUPPLEMENTAIRE ---
            if (isset($data['errors']) && !empty($data['errors'])) {
                echo "\n🔴 ERREUR API : \n";
                print_r($data['errors']);
                return [];
            }
            // ---------------------------

            $data = $response->toArray();
            // Pour getMatchDetails on veut un item, pour getFixtures on veut tout le tableau
            if (isset($query['id'])) {
                return $data['response'][0] ?? null;
            }
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }
}