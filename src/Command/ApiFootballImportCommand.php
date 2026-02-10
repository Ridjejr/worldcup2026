<?php

namespace App\Command;

use App\Entity\Edition;
use App\Entity\Equipe;
use App\Entity\Groupe;
use App\Entity\Phase;
use App\Entity\Participer;
use App\Entity\Stade;
use App\Entity\WorldcupMatch;
use App\Repository\EditionRepository;
use App\Repository\EquipeRepository;
use App\Repository\GroupeRepository;
use App\Repository\PhaseRepository;
use App\Repository\StadeRepository;
use App\Repository\WorldcupMatchRepository;
use App\Service\FootballApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:api-football:import',
    description: 'Importe fixtures depuis API-Football dans la BDD',
)]
class ApiFootballImportCommand extends Command
{
    public function __construct(
        private FootballApiService $api,
        private EntityManagerInterface $em,
        private EditionRepository $editionRepo,
        private PhaseRepository $phaseRepo,
        private GroupeRepository $groupeRepo,
        private EquipeRepository $equipeRepo,
        private StadeRepository $stadeRepo,
        private WorldcupMatchRepository $matchRepo,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        // Configuration via .env 
        $league = (int)($_ENV['APIFOOTBALL_LEAGUE_ID'] ?? 39); 
        $season = (int)($_ENV['APIFOOTBALL_SEASON'] ?? 2022);

        // 1. Edition & Phase
        $edition = $this->editionRepo->findOneBy(['annee' => 2026]);
        if (!$edition) {
            $edition = new Edition();
            $edition->setAnnee(2026);
            $edition->setNom('Coupe du Monde 2026');
            $edition->setDateDebut(new \DateTime('2026-06-11'));
            $edition->setDateFin(new \DateTime('2026-07-19'));
            $this->em->persist($edition);
        }

        $phase = $this->phaseRepo->findOneBy(['edition' => $edition, 'libelle' => 'Import API']);
        if (!$phase) {
            $phase = new Phase();
            $phase->setLibelle('Import API');
            $phase->setOrdre('99');
            $phase->setEdition($edition);
            $this->em->persist($phase);
        }
        
        // Groupe par défaut
        $groupeDefault = $this->groupeRepo->findOneBy(['nomGroupe' => 'A', 'phase' => $phase]);
        if (!$groupeDefault) {
            $groupeDefault = new Groupe();
            $groupeDefault->setNomGroupe('A');
            $groupeDefault->setClassement('0');
            $groupeDefault->setPhase($phase);
            $this->em->persist($groupeDefault);
        }

        $this->em->flush(); 

        $io->title("📡 Importation League $league - Saison $season");
        
        $data = $this->api->getFixtures($league, $season);

        if (!isset($data['response']) || !is_array($data['response'])) {
            $io->error('Réponse API invalide ou vide.');
            return Command::FAILURE;
        }

        $io->progressStart(count($data['response']));

        foreach ($data['response'] as $row) {
            $fixture = $row['fixture'];
            $teams = $row['teams'];
            $goals = $row['goals'];
            $apiFixtureId = $fixture['id'];

            // --- 1. Gestion Stade ---
            $venue = $fixture['venue'];
            $stadeNom = $venue['name'] ?? 'Stade inconnu';
            $stade = $this->stadeRepo->findOneBy(['nom' => $stadeNom]);
            
            if (!$stade) {
                $stade = new Stade();
                $stade->setNom($stadeNom);
                $stade->setVille($venue['city'] ?? 'Inconnue');
                $stade->setPays('N/A');
                $stade->setCapacite(50000);
                $this->em->persist($stade);
                $this->em->flush(); // Flush immédiat pour éviter doublons stade
            }

            // --- 2. Gestion Equipes ---
            $homeCode = substr(strtoupper($teams['home']['name']), 0, 3);
            $awayCode = substr(strtoupper($teams['away']['name']), 0, 3);

            $equipeHome = $this->findOrCreateEquipe($teams['home']['name'], $homeCode, $groupeDefault);
            $equipeAway = $this->findOrCreateEquipe($teams['away']['name'], $awayCode, $groupeDefault);

            // --- 3. Gestion Match ---
            $match = $this->matchRepo->findOneBy(['apiFixtureId' => $apiFixtureId]);

            // Si pas trouvé par ID API, on essaie Date + Stade
            if (!$match) {
                $dateHeure = new \DateTime($fixture['date']);
                $match = $this->matchRepo->findOneBy([
                    'dateHeure' => $dateHeure,
                    'stade' => $stade
                ]);
            }

            if (!$match) {
                $match = new WorldcupMatch();
                $match->setPhase($phase);
            }

            $match->setApiFixtureId($apiFixtureId);
            $match->setDateHeure(new \DateTime($fixture['date']));
            $match->setStade($stade);
            
            // Statut
            $shortStatus = $fixture['status']['short'];
            if (in_array($shortStatus, ['FT', 'AET', 'PEN'])) {
                $match->setStatut('TERMINE');
            } elseif (in_array($shortStatus, ['1H', '2H', 'HT', 'ET', 'P', 'LIVE'])) {
                $match->setStatut('EN_COURS');
            } else {
                $match->setStatut('A_VENIR');
            }

            $this->em->persist($match);

            // --- 4. Gestion Participations (CORRECTION ICI) ---
            // On utilise une méthode sécurisée qui vérifie si la liaison existe déjà
            $this->updateOrCreateParticipation($match, $equipeHome, 'DOMICILE', $goals['home']);
            $this->updateOrCreateParticipation($match, $equipeAway, 'EXTERIEUR', $goals['away']);

            $io->progressAdvance();
        }

        $this->em->flush();
        $io->progressFinish();
        $io->success('Import terminé avec succès !');

        return Command::SUCCESS;
    }

    private function findOrCreateEquipe(string $nom, string $code, Groupe $groupe): Equipe
    {
        $equipe = $this->equipeRepo->findOneBy(['nomEquipe' => $nom]);
        if (!$equipe) {
             $equipe = $this->equipeRepo->findOneBy(['codePays' => $code]);
        }

        if (!$equipe) {
            $equipe = new Equipe();
            $equipe->setNomEquipe($nom);
            $equipe->setCodePays($code);
            $equipe->setGroupe($groupe);
            $this->em->persist($equipe);
            $this->em->flush();
        }

        return $equipe;
    }

    // --- NOUVELLE MÉTHODE SÉCURISÉE ---
    private function updateOrCreateParticipation(WorldcupMatch $match, Equipe $equipe, string $role, ?int $buts): void
    {
        // 1. On cherche si cette équipe participe DÉJÀ à ce match
        // (On utilise la collection Doctrine pour éviter une requête SQL si c'est déjà chargé)
        $existingParticipation = null;
        
        foreach ($match->getParticipations() as $p) {
            // Si l'ID de l'équipe correspond, c'est qu'elle est déjà là
            if ($p->getEquipe()->getId() === $equipe->getId()) {
                $existingParticipation = $p;
                break;
            }
        }

        if ($existingParticipation) {
            // MISE A JOUR
            $existingParticipation->setRole($role);
            $existingParticipation->setButs($buts);
        } else {
            // CRÉATION (seulement si n'existe pas)
            $participation = new Participer();
            $participation->setMatch($match);
            $participation->setEquipe($equipe);
            $participation->setRole($role);
            $participation->setButs($buts);
            $participation->setProlongation(false);
            $this->em->persist($participation);
            
            // Important: ajouter à la collection du match en mémoire pour les prochains checks
            $match->addParticipation($participation);
        }
    }
}