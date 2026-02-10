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
use App\Service\ApiFootballClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:api-football:import',
    description: 'Importe fixtures depuis API-Football dans la BDD',
)]
class ApiFootballImportCommand extends Command
{
    public function __construct(
        private ApiFootballClient $api,
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
        $league = (int)($_ENV['APIFOOTBALL_LEAGUE_ID'] ?? 0);
        $season = (int)($_ENV['APIFOOTBALL_SEASON'] ?? 0);

        if ($league <= 0 || $season <= 0) {
            $output->writeln('<error>APIFOOTBALL_LEAGUE_ID / APIFOOTBALL_SEASON manquants dans .env.local</error>');
            return Command::FAILURE;
        }

        // 1) Edition (2026)
        $edition = $this->editionRepo->findOneBy(['annee' => 2026]);
        if (!$edition) {
            $edition = new Edition();
            $edition->setAnnee(2026);
            $edition->setNom('Coupe du Monde 2026');
            $edition->setDateDebut(new \DateTime('2026-06-11'));
            $edition->setDateFin(new \DateTime('2026-07-19'));
            $this->em->persist($edition);
        }

        // 2) Phase "Import API" (pour commencer simple)
        $phase = $this->phaseRepo->findOneBy(['edition' => $edition, 'libelle' => 'Import API']);
        if (!$phase) {
            $phase = new Phase();
            $phase->setLibelle('Import API');
            $phase->setOrdre(99);
            $phase->setEdition($edition);
            $this->em->persist($phase);
        }

        $output->writeln("📡 Appel API fixtures league=$league season=$season ...");
        $data = $this->api->getFixtures($league, $season);

        if (!isset($data['response']) || !is_array($data['response'])) {
            $output->writeln('<error>Réponse API invalide</error>');
            return Command::FAILURE;
        }

        $count = 0;

        foreach ($data['response'] as $row) {
            $fixture = $row['fixture'] ?? null;
            $teams = $row['teams'] ?? null;
            $goals = $row['goals'] ?? null;

            if (!$fixture || !$teams) continue;

            // --- Stade ---
            $venue = $fixture['venue'] ?? [];
            $stadeNom = $venue['name'] ?? 'Stade inconnu';
            $stadeVille = $venue['city'] ?? 'Ville inconnue';
            $stadePays = 'N/A';

            $stade = $this->stadeRepo->findOneBy(['nom' => $stadeNom, 'ville' => $stadeVille]);
            if (!$stade) {
                $stade = new Stade();
                $stade->setNom($stadeNom);
                $stade->setVille($stadeVille);
                $stade->setPays($stadePays);
                $this->em->persist($stade);
            }

            // --- Equipes ---
            $home = $teams['home'] ?? [];
            $away = $teams['away'] ?? [];

            $homeName = $home['name'] ?? 'Home';
            $awayName = $away['name'] ?? 'Away';

            // code_pays : l’API n’a pas toujours un code FIFA => on met les 3 premières lettres
            $homeCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $homeName), 0, 3));
            $awayCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $awayName), 0, 3));

            // Groupe par défaut (si standings pas importés)
            $groupeDefault = $this->groupeRepo->findOneBy(['nomGroupe' => 'X', 'phase' => $phase]);
            if (!$groupeDefault) {
                $groupeDefault = new Groupe();
                $groupeDefault->setNomGroupe('X');
                $groupeDefault->setPhase($phase);
                $this->em->persist($groupeDefault);
            }

            $equipeHome = $this->equipeRepo->findOneBy(['codePays' => $homeCode]);
            if (!$equipeHome) {
                $equipeHome = new Equipe();
                $equipeHome->setNomEquipe($homeName);
                $equipeHome->setCodePays($homeCode);
                $equipeHome->setGroupe($groupeDefault);
                $this->em->persist($equipeHome);
            }

            $equipeAway = $this->equipeRepo->findOneBy(['codePays' => $awayCode]);
            if (!$equipeAway) {
                $equipeAway = new Equipe();
                $equipeAway->setNomEquipe($awayName);
                $equipeAway->setCodePays($awayCode);
                $equipeAway->setGroupe($groupeDefault);
                $this->em->persist($equipeAway);
            }

            // --- Match ---
            $dateIso = $fixture['date'] ?? null;
            if (!$dateIso) continue;

            $dateHeure = new \DateTime($dateIso);

            // éviter doublons : même date + mêmes équipes
            $existing = $this->matchRepo->findOneBy([
                'dateHeure' => $dateHeure,
                'phase' => $phase,
                'stade' => $stade,
            ]);

            if ($existing) {
                continue;
            }

            $match = new WorldcupMatch();
            $match->setDateHeure($dateHeure);
            $match->setPhase($phase);
            $match->setStade($stade);

            // statut
            $status = $fixture['status']['short'] ?? 'NS'; // NS, 1H, 2H, FT...
            $statut = 'A_VENIR';
            if (in_array($status, ['1H','2H','HT','ET','BT','P','LIVE'])) $statut = 'EN_COURS';
            if (in_array($status, ['FT','AET','PEN'])) $statut = 'TERMINE';
            $match->setStatut($statut);

            $this->em->persist($match);

            // --- Participer domicile / extérieur ---
            $pHome = new Participer();
            $pHome->setMatch($match);
            $pHome->setEquipe($equipeHome);
            $pHome->setRole('DOMICILE');
            $pHome->setProlongation(false);
            $pHome->setButs($goals['home'] ?? null);
            $this->em->persist($pHome);

            $pAway = new Participer();
            $pAway->setMatch($match);
            $pAway->setEquipe($equipeAway);
            $pAway->setRole('EXTERIEUR');
            $pAway->setProlongation(false);
            $pAway->setButs($goals['away'] ?? null);
            $this->em->persist($pAway);

            $count++;

            if ($count % 50 === 0) {
                $this->em->flush();
                $this->em->clear();
                $output->writeln("✅ $count matchs importés...");
            }
        }

        $this->em->flush();

        $output->writeln("✅ Import terminé : $count matchs importés.");
        return Command::SUCCESS;
    }
}
