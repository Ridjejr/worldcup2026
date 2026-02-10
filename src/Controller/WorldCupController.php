<?php
// src/Controller/WorldCupController.php
namespace App\Controller;

use App\Repository\EditionRepository;
use App\Repository\GroupeRepository;
use App\Repository\ParticiperRepository;
use App\Repository\PhaseRepository;
use App\Repository\WorldcupMatchRepository;
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
                $matchesData[] = [
                    'match' => $match,
                    'domicile' => $participations[0],
                    'exterieur' => $participations[1],
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
        ParticiperRepository $participerRepository
    ): Response {
        $match = $worldcupMatchRepository->find($id);
        if (!$match) {
            throw new NotFoundHttpException("Match introuvable");
        }

        $participations = $participerRepository->findBy(['match' => $match], ['role' => 'ASC']);

        $domicile = $participations[0] ?? null;
        $exterieur = $participations[1] ?? null;

        return $this->render('world_cup/match_detail.html.twig', [
            'match' => $match,
            'domicile' => $domicile,
            'exterieur' => $exterieur,
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
