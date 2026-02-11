<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Repository\WorldcupMatchRepository;

class ApiFootballImportCommandTest extends KernelTestCase
{
    public function testExecuteImport(): void
{
    self::bootKernel();
    $container = static::getContainer();

    $apiMock = $this->createMock(\App\Service\FootballApiService::class);
    // On simule une réponse positive avec un match fictif
    $apiMock->method('getFixtures')->willReturn([
        'response' => [
            [
                'fixture' => [
                    'id' => 123, 
                    'date' => '2026-06-11T20:00:00+00:00', 
                    'status' => ['short' => 'NS'],
                    'venue' => ['name' => 'Azteca', 'city' => 'Mexico']
                ],
                'teams' => [
                    'home' => ['name' => 'MEXICO'],
                    'away' => ['name' => 'USA']
                ],
                'goals' => ['home' => null, 'away' => null]
            ]
        ]
    ]);

    // On remplace le vrai service par le Mock dans le container de test
    $container->set(\App\Service\FootballApiService::class, $apiMock);

    $application = new Application(self::$kernel);
    $command = $application->find('app:api-football:import');
    $commandTester = new CommandTester($command);

    $commandTester->execute([]);

    $output = $commandTester->getDisplay();
    $this->assertStringContainsString('Import terminé avec succès', $output);
}
}