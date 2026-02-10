<?php
// src/Controller/WorldCupController.php
namespace App\Controller;

use App\Repository\WorldcupMatchRepository;
use App\Repository\GroupeRepository;
use App\Repository\EquipeRepository;
use App\Repository\EditionRepository;
use App\Repository\ParticiperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WorldCupController extends AbstractController 
{
    #[Route('/', name: 'app_home')]
    public function index(
        WorldcupMatchRepository $worldcupMatchRepository, 
        ParticiperRepository $participerRepository,
        EditionRepository $editionRepository
    ): Response {
        // Récupérer l'édition actuelle
        $edition = $editionRepository->findOneBy(['annee' => 2026]);
        
        // Récupérer tous les matchs triés par date
        $matches = $worldcupMatchRepository->findBy([], ['dateHeure' => 'ASC']);
        
        // Grouper les participations par match avec l'ordre DOMICILE puis EXTERIEUR
        $matchesData = [];
        foreach ($matches as $match) {
            $participations = $participerRepository->findBy(
                ['match' => $match],
                ['role' => 'ASC'] // DOMICILE avant EXTERIEUR
            );
            
            if (count($participations) === 2) {
                $matchesData[] = [
                    'match' => $match,
                    'domicile' => $participations[0], // DOMICILE
                    'exterieur' => $participations[1], // EXTERIEUR
                ];
            }
        }
        
        return $this->render('world_cup/index.html.twig', [
            'edition' => $edition,
            'matchesData' => $matchesData,
        ]);
    }
}