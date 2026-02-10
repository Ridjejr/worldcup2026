<?php
namespace App\Controller;

use App\Repository\WorldcupMatchRepository;
use App\Repository\ParticiperRepository;
use App\Repository\EditionRepository;
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
        // ===== Mapping manuel code FIFA -> emoji drapeau =====
        $fifaToEmoji = [
            'MEX' => '🇲🇽',
            'ECU' => '🇪🇨',
            'JPN' => '🇯🇵',
            'AUS' => '🇦🇺',
            'USA' => '🇺🇸',
            'NED' => '🇳🇱',
            'ITA' => '🇮🇹',
            'NZL' => '🇳🇿',
            'ARG' => '🇦🇷',
            'DEN' => '🇩🇰',
            'PER' => '🇵🇪',
            'CMR' => '🇨🇲',
            'FRA' => '🇫🇷',
            'ENG' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿',
            'SUI' => '🇨🇭',
            'KOR' => '🇰🇷',
            'BRA' => '🇧🇷',
            'ESP' => '🇪🇸',
            'CRO' => '🇭🇷',
            'MAR' => '🇲🇦',
            'BEL' => '🇧🇪',
            'POR' => '🇵🇹',
            'SEN' => '🇸🇳',
            'CAN' => '🇨🇦',
            'GER' => '🇩🇪',
            'URU' => '🇺🇾',
            'COL' => '🇨🇴',
            'CIV' => '🇨🇮',
            'POL' => '🇵🇱',
            'SWE' => '🇸🇪',
            'EGY' => '🇪🇬',
            'KSA' => '🇸🇦',
            'SRB' => '🇷🇸',
            'NGA' => '🇳🇬',
            'TUN' => '🇹🇳',
            'IRN' => '🇮🇷',
            'NOR' => '🇳🇴',
            'ALG' => '🇩🇿',
            'GHA' => '🇬🇭',
            'QAT' => '🇶🇦',
            'TUR' => '🇹🇷',
            'AUT' => '🇦🇹',
            'RSA' => '🇿🇦',
            'ISL' => '🇮🇸',
            'CHI' => '🇨🇱',
            'CZE' => '🇨🇿',
            'ROU' => '🇷🇴',
            'CRC' => '🇨🇷',
        ];

        // Récupérer l'édition actuelle
        $edition = $editionRepository->findOneBy(['annee' => 2026]);
        
        // Récupérer tous les matchs triés par date
        $matches = $worldcupMatchRepository->findBy([], ['dateHeure' => 'ASC']);
        
        // Grouper les participations par match
        $matchesData = [];
        foreach ($matches as $match) {
            $participations = $participerRepository->findBy(
                ['match' => $match],
                ['role' => 'ASC'] // DOMICILE avant EXTERIEUR
            );
            
            if (count($participations) === 2) {
                $domCode = $participations[0]->getEquipe()->getCodePays();
                $extCode = $participations[1]->getEquipe()->getCodePays();

                $matchesData[] = [
                    'match' => $match,
                    'domicile' => $participations[0],
                    'domicileDrapeau' => $fifaToEmoji[$domCode] ?? '🏳️',
                    'exterieur' => $participations[1],
                    'exterieurDrapeau' => $fifaToEmoji[$extCode] ?? '🏳️',
                ];
            }
        }

        return $this->render('world_cup/index.html.twig', [
            'edition' => $edition,
            'matchesData' => $matchesData,
        ]);
    }
}
