<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WorldCupControllerTest extends WebTestCase
{
    public function testHomePageResponse(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Vérifie que la page répond correctement
        $this->assertResponseIsSuccessful();

        // Vérifie le titre de la page
        $this->assertSelectorTextContains('title', 'Coupe du Monde 2026 - Accueil');

        // Vérifie que le logo ⚽ est présent
        $this->assertSelectorTextContains('.logo', '⚽');

        // Vérifie qu’au moins un onglet "Tous les matchs" existe
        $this->assertSelectorTextContains('.tab', 'Tous les matchs');

        // Vérifie qu’un match ou message "Aucun match" est présent
        $this->assertTrue(
            $crawler->filter('.match-card')->count() > 0 ||
            $crawler->filter('div:contains("Aucun match programmé")')->count() > 0
        );
    }
}
