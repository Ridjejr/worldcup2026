<?php
namespace App\Controller;

use App\Repository\WorldcupMatchRepository;
use App\Repository\ParticiperRepository;
use App\Repository\EditionRepository;
use App\Repository\PhaseRepository;
use App\Repository\GroupeRepository;
use App\Service\FootballApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class WorldCupController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        WorldcupMatchRepository $worldcupMatchRepository,
        ParticiperRepository $participerRepository,
        EditionRepository $editionRepository,
        PhaseRepository $phaseRepository
    ): Response {
        // ===== Mapping manuel code FIFA -> emoji drapeau =====
        $fifaToEmoji = [
            'MEX' => '🇲🇽', 'ECU' => '🇪🇨', 'JAP' => '🇯🇵', 'AUS' => '🇦🇺', 'USA' => '🇺🇸', 'NET' => '🇳🇱',
            'ITA' => '🇮🇹', 'NZL' => '🇳🇿', 'ARG' => '🇦🇷', 'DEN' => '🇩🇰', 'PER' => '🇵🇪', 'CAM' => '🇨🇲',
            'FRA' => '🇫🇷', 'ENG' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿', 'SUI' => '🇨🇭', 'SOU' => '🇰🇷', 'BRA' => '🇧🇷', 'ESP' => '🇪🇸',
            'CRO' => '🇭🇷', 'MAR' => '🇲🇦', 'BEL' => '🇧🇪', 'POR' => '🇵🇹', 'SEN' => '🇸🇳', 'CAN' => '🇨🇦',
            'GER' => '🇩🇪', 'URU' => '🇺🇾', 'COL' => '🇨🇴', 'CIV' => '🇨🇮', 'POL' => '🇵🇱', 'SWE' => '🇸🇪',
            'EGY' => '🇪🇬', 'SAU' => '🇸🇦', 'SRB' => '🇷🇸', 'NGA' => '🇳🇬', 'TUN' => '🇹🇳', 'IRA' => '🇮🇷',
            'NOR' => '🇳🇴', 'ALG' => '🇩🇿', 'GHA' => '🇬🇭', 'QAT' => '🇶🇦', 'TUR' => '🇹🇷', 'AUT' => '🇦🇹',
            'RSA' => '🇿🇦', 'ISL' => '🇮🇸', 'CHI' => '🇨🇱', 'CZE' => '🇨🇿', 'ROU' => '🇷🇴', 'COS' => '🇨🇷',
        ];

        // Récupérer l'édition actuelle
        $edition = $editionRepository->findOneBy(['annee' => 2026]);
        if (!$edition) {
            throw new NotFoundHttpException("Edition 2026 introuvable");
        }

        // Toutes les phases (pour les tabs)
        $phases = $phaseRepository->findBy(['edition' => $edition], ['ordre' => 'ASC']);

        // Filtre par phase via ?phase=ID
        $phaseId = $request->query->get('phase');
        $selectedPhase = null;
        if ($phaseId) {
            $selectedPhase = $phaseRepository->find((int)$phaseId);
        }

        $matches = $worldcupMatchRepository->findByPhase($selectedPhase);

        $matchesData = [];
        foreach ($matches as $match) {
            $participations = $participerRepository->findBy(
                ['match' => $match],
                ['role' => 'ASC'] // DOMICILE avant EXTERIEUR (si tri alpha)
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
            'phases' => $phases,
            'selectedPhase' => $selectedPhase,
            'matchesData' => $matchesData,
        ]);
    }

    #[Route('/groupes', name: 'app_groupes')]
    public function groupes(
        EditionRepository $editionRepository,
        PhaseRepository $phaseRepository,
        GroupeRepository $groupeRepository
    ): Response {
        $edition = $editionRepository->findOneBy(['annee' => 2026]);
        if (!$edition) {
            throw new NotFoundHttpException("Edition 2026 introuvable");
        }

        // phase "Phase de groupes"
        $phaseGroupes = $phaseRepository->findOneBy(['edition' => $edition, 'libelle' => 'Phase de groupes']);

        $groupes = [];
        if ($phaseGroupes) {
            $groupes = $groupeRepository->findBy(['phase' => $phaseGroupes], ['nomGroupe' => 'ASC']);
        }

        return $this->render('world_cup/groupes.html.twig', [
            'edition' => $edition,
            'groupes' => $groupes,
        ]);
    }

   #[Route('/match/{id}', name: 'app_match_detail', requirements: ['id' => '\d+'])]
    public function matchDetail(
        int $id,
        WorldcupMatchRepository $worldcupMatchRepository,
        ParticiperRepository $participerRepository,
        FootballApiService $apiService, // <--- Injection du service
        Request $request
    ): Response {
        $match = $worldcupMatchRepository->find($id);
        if (!$match) {
            throw new NotFoundHttpException("Match introuvable");
        }

        // Récupération des participations locales (BDD)
        $participations = $participerRepository->findBy(['match' => $match], ['role' => 'ASC']);
        $domicile = $participations[0] ?? null;
        $exterieur = $participations[1] ?? null;

        // ===== Mapping Emojis (nécessaire ici aussi pour l'affichage) =====
        $fifaToEmoji = [
            'MEX' => '🇲🇽', 'ECU' => '🇪🇨', 'JPN' => '🇯🇵', 'AUS' => '🇦🇺', 'USA' => '🇺🇸', 'NED' => '🇳🇱',
            'ITA' => '🇮🇹', 'NZL' => '🇳🇿', 'ARG' => '🇦🇷', 'DEN' => '🇩🇰', 'PER' => '🇵🇪', 'CMR' => '🇨🇲',
            'FRA' => '🇫🇷', 'ENG' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿', 'SUI' => '🇨🇭', 'KOR' => '🇰🇷', 'BRA' => '🇧🇷', 'ESP' => '🇪🇸',
            'CRO' => '🇭🇷', 'MAR' => '🇲🇦', 'BEL' => '🇧🇪', 'POR' => '🇵🇹', 'SEN' => '🇸🇳', 'CAN' => '🇨🇦',
            'GER' => '🇩🇪', 'URU' => '🇺🇾', 'COL' => '🇨🇴', 'CIV' => '🇨🇮', 'POL' => '🇵🇱', 'SWE' => '🇸🇪',
            'EGY' => '🇪🇬', 'KSA' => '🇸🇦', 'SRB' => '🇷🇸', 'NGA' => '🇳🇬', 'TUN' => '🇹🇳', 'IRN' => '🇮🇷',
            'NOR' => '🇳🇴', 'ALG' => '🇩🇿', 'GHA' => '🇬🇭', 'QAT' => '🇶🇦', 'TUR' => '🇹🇷', 'AUT' => '🇦🇹',
            'RSA' => '🇿🇦', 'ISL' => '🇮🇸', 'CHI' => '🇨🇱', 'CZE' => '🇨🇿', 'ROU' => '🇷🇴', 'CRC' => '🇨🇷',
        ];

        // ===== APPEL API FOOTBALL =====
        $liveData = null;
        // On vérifie si vous avez bien ajouté le champ apiFixtureId dans votre entité WorldcupMatch
        // et qu'il est rempli pour ce match.
        if (method_exists($match, 'getApiFixtureId') && $match->getApiFixtureId()) {
            $liveData = $apiService->getMatchDetails($match->getApiFixtureId());
        }

        // =========================================================
        // 🔴 MODE SIMULATION TEMPORELLE (Time Machine)
        // =========================================================
        if ($liveData && $request->query->get('demo') === 'live') {
            $session = $request->getSession();

            // Si on demande ?reset=1, on remet le chrono à zéro
            if ($request->query->get('reset')) {
                $session->remove('demo_start_time');
            }

            // 1. Initialiser le début de la simulation
            if (!$session->has('demo_start_time')) {
                $session->set('demo_start_time', time());
            }

            // 2. Calculer le temps écoulé (en secondes réelles)
            $timeElapsedReal = time() - $session->get('demo_start_time');

            // 3. Accélérateur : 1 seconde réelle = 1 minute de match !
            // (Pour voir tout le match en 1min30)
            // Vous pouvez changer le facteur (ex: / 2 pour 1 min match = 2 sec réel)
            $matchMinute = 1 + floor($timeElapsedReal / 1); 

            // On s'arrête à 90' ou 120'
            if ($matchMinute > 120) $matchMinute = 120;

            // Mise à jour du statut API
            $liveData['fixture']['status']['elapsed'] = $matchMinute;
            
            // Gestion Mi-temps / Fin
            if ($matchMinute < 45) {
                $liveData['fixture']['status']['short'] = '1H';
                $liveData['fixture']['status']['long'] = 'Première Mi-temps';
            } elseif ($matchMinute < 90) {
                $liveData['fixture']['status']['short'] = '2H';
                $liveData['fixture']['status']['long'] = 'Seconde Mi-temps';
            } else {
                 $liveData['fixture']['status']['short'] = 'ET'; // Prolongations
            }

            // 4. FILTRER LES ÉVÉNEMENTS ET RECALCULER LE SCORE
            // C'est crucial : le score doit évoluer avec le temps !
            $allEvents = $liveData['events'] ?? [];
            $filteredEvents = [];
            $homeScore = 0;
            $awayScore = 0;

            // On récupère les ID ou Noms des équipes pour savoir à qui donner le but
            $homeTeamName = $liveData['teams']['home']['name'];

            foreach ($allEvents as $event) {
                // On garde l'événement seulement s'il s'est déjà produit dans notre "temps simulé"
                if ($event['time']['elapsed'] <= $matchMinute) {
                    $filteredEvents[] = $event;

                    // Si c'est un but, on met à jour le score simulé
                    if ($event['type'] === 'Goal') {
                        if ($event['team']['name'] === $homeTeamName) {
                            $homeScore++;
                        } else {
                            $awayScore++;
                        }
                    }
                }
            }

            // On écrase les données de l'API avec nos données simulées
            $liveData['events'] = $filteredEvents;
            $liveData['goals']['home'] = $homeScore;
            $liveData['goals']['away'] = $awayScore;
        }

        return $this->render('world_cup/match_detail.html.twig', [
            'match' => $match,
            'domicile' => $domicile,
            'exterieur' => $exterieur,
            'domicileDrapeau' => $domicile ? ($fifaToEmoji[$domicile->getEquipe()->getCodePays()] ?? '🏳️') : '🏳️',
            'exterieurDrapeau' => $exterieur ? ($fifaToEmoji[$exterieur->getEquipe()->getCodePays()] ?? '🏳️') : '🏳️',
            'liveData' => $liveData,
        ]);
    }

    /**
     * Endpoint JSON pour polling "temps réel"
     * (score + statut + équipes)
     */
    #[Route('/api/match/{id}/live', name: 'api_match_live', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function matchLive(
        int $id,
        WorldcupMatchRepository $worldcupMatchRepository,
        ParticiperRepository $participerRepository
    ): JsonResponse {
        $match = $worldcupMatchRepository->find($id);
        if (!$match) {
            return new JsonResponse(['error' => 'Match introuvable'], 404);
        }

        $participations = $participerRepository->findBy(['match' => $match], ['role' => 'ASC']);
        $dom = $participations[0] ?? null;
        $ext = $participations[1] ?? null;

        return new JsonResponse([
            'id' => $match->getId(),
            'statut' => $match->getStatut(),
            'dateHeure' => $match->getDateHeure()?->format('Y-m-d H:i:s'),
            'phase' => $match->getPhase()?->getLibelle(),
            'stade' => [
                'nom' => $match->getStade()?->getNom(),
                'ville' => $match->getStade()?->getVille(),
            ],
            'domicile' => $dom ? [
                'nom' => $dom->getEquipe()?->getNomEquipe(),
                'code' => $dom->getEquipe()?->getCodePays(),
                'drapeau' => $dom->getEquipe()?->getDrapeau(),
                'buts' => $dom->getButs(),
                'tirAuBut' => $dom->getTirAuBut(),
            ] : null,
            'exterieur' => $ext ? [
                'nom' => $ext->getEquipe()?->getNomEquipe(),
                'code' => $ext->getEquipe()?->getCodePays(),
                'drapeau' => $ext->getEquipe()?->getDrapeau(),
                'buts' => $ext->getButs(),
                'tirAuBut' => $ext->getTirAuBut(),
            ] : null,
        ]);
    }
}
