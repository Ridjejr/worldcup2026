<?php

namespace App\DataFixtures;

use App\Entity\Edition;
use App\Entity\Phase;
use App\Entity\Groupe;
use App\Entity\Stade;
use App\Entity\Equipe;
use App\Entity\WorldcupMatch;
use App\Entity\Participer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Worldcup2026fixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // ===== EDITION =====
        $edition = new Edition();
        $edition->setAnnee(2026);
        $edition->setNom('Coupe du Monde de la FIFA 2026');
        $edition->setDateDebut(new \DateTime('2026-06-11'));
        $edition->setDateFin(new \DateTime('2026-07-19'));
        $manager->persist($edition);

        // ===== PHASE DE GROUPES =====
        $phaseGroupes = new Phase();
        $phaseGroupes->setLibelle('Phase de groupes');
        $phaseGroupes->setOrdre(1);
        $phaseGroupes->setEdition($edition);
        $manager->persist($phaseGroupes);

        // ===== STADES (USA, Canada, Mexique) =====
        $stades = [];
        
        // USA
        $stades['MetLife'] = $this->createStade($manager, 'MetLife Stadium', 'New York/New Jersey', 'USA', 82500);
        $stades['ATT'] = $this->createStade($manager, 'AT&T Stadium', 'Arlington', 'USA', 80000);
        $stades['Arrowhead'] = $this->createStade($manager, 'Arrowhead Stadium', 'Kansas City', 'USA', 76416);
        $stades['SoFi'] = $this->createStade($manager, 'SoFi Stadium', 'Los Angeles', 'USA', 70240);
        $stades['MercedesBenz'] = $this->createStade($manager, 'Mercedes-Benz Stadium', 'Atlanta', 'USA', 71000);
        $stades['NRG'] = $this->createStade($manager, 'NRG Stadium', 'Houston', 'USA', 72220);
        $stades['Lumen'] = $this->createStade($manager, 'Lumen Field', 'Seattle', 'USA', 69000);
        $stades['Levi'] = $this->createStade($manager, 'Levi\'s Stadium', 'Santa Clara', 'USA', 68500);
        $stades['Lincoln'] = $this->createStade($manager, 'Lincoln Financial Field', 'Philadelphie', 'USA', 69176);
        $stades['HardRock'] = $this->createStade($manager, 'Hard Rock Stadium', 'Miami', 'USA', 65326);
        $stades['Gillette'] = $this->createStade($manager, 'Gillette Stadium', 'Boston', 'USA', 65878);
        
        // Mexique
        $stades['Azteca'] = $this->createStade($manager, 'Estadio Azteca', 'Mexico City', 'Mexique', 87523);
        $stades['Guadalajara'] = $this->createStade($manager, 'Estadio Akron', 'Guadalajara', 'Mexique', 46232);
        $stades['Monterrey'] = $this->createStade($manager, 'Estadio BBVA', 'Monterrey', 'Mexique', 53500);
        
        // Canada
        $stades['BMO'] = $this->createStade($manager, 'BMO Field', 'Toronto', 'Canada', 45500);
        $stades['BC'] = $this->createStade($manager, 'BC Place', 'Vancouver', 'Canada', 54500);

        // ===== GROUPES ET ÉQUIPES =====
        // Les vrais groupes de la Coupe du Monde 2026
        
        // GROUPE A
        $groupeA = $this->createGroupe($manager, 'A', $phaseGroupes);
        $equipes['A'] = [
            $this->createEquipe($manager, 'Mexique', 'MEX', $groupeA),
            $this->createEquipe($manager, 'Équateur', 'ECU', $groupeA),
            $this->createEquipe($manager, 'Japon', 'JPN', $groupeA),
            $this->createEquipe($manager, 'Australie', 'AUS', $groupeA),
        ];

        // GROUPE B
        $groupeB = $this->createGroupe($manager, 'B', $phaseGroupes);
        $equipes['B'] = [
            $this->createEquipe($manager, 'États-Unis', 'USA', $groupeB),
            $this->createEquipe($manager, 'Pays-Bas', 'NED', $groupeB),
            $this->createEquipe($manager, 'Italie', 'ITA', $groupeB),
            $this->createEquipe($manager, 'Nouvelle-Zélande', 'NZL', $groupeB),
        ];

        // GROUPE C
        $groupeC = $this->createGroupe($manager, 'C', $phaseGroupes);
        $equipes['C'] = [
            $this->createEquipe($manager, 'Argentine', 'ARG', $groupeC),
            $this->createEquipe($manager, 'Danemark', 'DEN', $groupeC),
            $this->createEquipe($manager, 'Pérou', 'PER', $groupeC),
            $this->createEquipe($manager, 'Cameroun', 'CMR', $groupeC),
        ];

        // GROUPE D
        $groupeD = $this->createGroupe($manager, 'D', $phaseGroupes);
        $equipes['D'] = [
            $this->createEquipe($manager, 'France', 'FRA', $groupeD),
            $this->createEquipe($manager, 'Angleterre', 'ENG', $groupeD),
            $this->createEquipe($manager, 'Suisse', 'SUI', $groupeD),
            $this->createEquipe($manager, 'Corée du Sud', 'KOR', $groupeD),
        ];

        // GROUPE E
        $groupeE = $this->createGroupe($manager, 'E', $phaseGroupes);
        $equipes['E'] = [
            $this->createEquipe($manager, 'Brésil', 'BRA', $groupeE),
            $this->createEquipe($manager, 'Espagne', 'ESP', $groupeE),
            $this->createEquipe($manager, 'Croatie', 'CRO', $groupeE),
            $this->createEquipe($manager, 'Maroc', 'MAR', $groupeE),
        ];

        // GROUPE F
        $groupeF = $this->createGroupe($manager, 'F', $phaseGroupes);
        $equipes['F'] = [
            $this->createEquipe($manager, 'Belgique', 'BEL', $groupeF),
            $this->createEquipe($manager, 'Portugal', 'POR', $groupeF),
            $this->createEquipe($manager, 'Sénégal', 'SEN', $groupeF),
            $this->createEquipe($manager, 'Canada', 'CAN', $groupeF),
        ];

        // GROUPE G
        $groupeG = $this->createGroupe($manager, 'G', $phaseGroupes);
        $equipes['G'] = [
            $this->createEquipe($manager, 'Allemagne', 'GER', $groupeG),
            $this->createEquipe($manager, 'Uruguay', 'URU', $groupeG),
            $this->createEquipe($manager, 'Colombie', 'COL', $groupeG),
            $this->createEquipe($manager, 'Côte d\'Ivoire', 'CIV', $groupeG),
        ];

        // GROUPE H
        $groupeH = $this->createGroupe($manager, 'H', $phaseGroupes);
        $equipes['H'] = [
            $this->createEquipe($manager, 'Pologne', 'POL', $groupeH),
            $this->createEquipe($manager, 'Suède', 'SWE', $groupeH),
            $this->createEquipe($manager, 'Égypte', 'EGY', $groupeH),
            $this->createEquipe($manager, 'Arabie Saoudite', 'KSA', $groupeH),
        ];

        // GROUPE I
        $groupeI = $this->createGroupe($manager, 'I', $phaseGroupes);
        $equipes['I'] = [
            $this->createEquipe($manager, 'Serbie', 'SRB', $groupeI),
            $this->createEquipe($manager, 'Nigeria', 'NGA', $groupeI),
            $this->createEquipe($manager, 'Tunisie', 'TUN', $groupeI),
            $this->createEquipe($manager, 'Iran', 'IRN', $groupeI),
        ];

        // GROUPE J
        $groupeJ = $this->createGroupe($manager, 'J', $phaseGroupes);
        $equipes['J'] = [
            $this->createEquipe($manager, 'Norvège', 'NOR', $groupeJ),
            $this->createEquipe($manager, 'Algérie', 'ALG', $groupeJ),
            $this->createEquipe($manager, 'Ghana', 'GHA', $groupeJ),
            $this->createEquipe($manager, 'Qatar', 'QAT', $groupeJ),
        ];

        // GROUPE K
        $groupeK = $this->createGroupe($manager, 'K', $phaseGroupes);
        $equipes['K'] = [
            $this->createEquipe($manager, 'Turquie', 'TUR', $groupeK),
            $this->createEquipe($manager, 'Autriche', 'AUT', $groupeK),
            $this->createEquipe($manager, 'Afrique du Sud', 'RSA', $groupeK),
            $this->createEquipe($manager, 'Islande', 'ISL', $groupeK),
        ];

        // GROUPE L
        $groupeL = $this->createGroupe($manager, 'L', $phaseGroupes);
        $equipes['L'] = [
            $this->createEquipe($manager, 'Chili', 'CHI', $groupeL),
            $this->createEquipe($manager, 'République Tchèque', 'CZE', $groupeL),
            $this->createEquipe($manager, 'Roumanie', 'ROU', $groupeL),
            $this->createEquipe($manager, 'Costa Rica', 'CRC', $groupeL),
        ];

        // ===== MATCHS - JOURNÉE 1 =====
        $dateJ1 = new \DateTime('2026-06-11 21:00:00');
        
        // Groupe A - J1
        $this->createMatch($manager, $phaseGroupes, $stades['Azteca'], $dateJ1, 
            $equipes['A'][0], $equipes['A'][2]); // Mexique vs Japon
        $this->createMatch($manager, $phaseGroupes, $stades['ATT'], clone $dateJ1->modify('+3 hours'), 
            $equipes['A'][1], $equipes['A'][3]); // Équateur vs Australie

        // Groupe B - J1
        $this->createMatch($manager, $phaseGroupes, $stades['SoFi'], new \DateTime('2026-06-12 18:00:00'), 
            $equipes['B'][0], $equipes['B'][3]); // USA vs Nouvelle-Zélande
        $this->createMatch($manager, $phaseGroupes, $stades['MetLife'], new \DateTime('2026-06-12 21:00:00'), 
            $equipes['B'][1], $equipes['B'][2]); // Pays-Bas vs Italie

        // Groupe C - J1
        $this->createMatch($manager, $phaseGroupes, $stades['HardRock'], new \DateTime('2026-06-13 15:00:00'), 
            $equipes['C'][0], $equipes['C'][3]); // Argentine vs Cameroun
        $this->createMatch($manager, $phaseGroupes, $stades['NRG'], new \DateTime('2026-06-13 18:00:00'), 
            $equipes['C'][1], $equipes['C'][2]); // Danemark vs Pérou

        // Groupe D - J1
        $this->createMatch($manager, $phaseGroupes, $stades['MercedesBenz'], new \DateTime('2026-06-13 21:00:00'), 
            $equipes['D'][0], $equipes['D'][3]); // France vs Corée du Sud
        $this->createMatch($manager, $phaseGroupes, $stades['Gillette'], new \DateTime('2026-06-14 15:00:00'), 
            $equipes['D'][1], $equipes['D'][2]); // Angleterre vs Suisse

        // Groupe E - J1
        $this->createMatch($manager, $phaseGroupes, $stades['Levi'], new \DateTime('2026-06-14 18:00:00'), 
            $equipes['E'][0], $equipes['E'][3]); // Brésil vs Maroc
        $this->createMatch($manager, $phaseGroupes, $stades['Arrowhead'], new \DateTime('2026-06-14 21:00:00'), 
            $equipes['E'][1], $equipes['E'][2]); // Espagne vs Croatie

        // Groupe F - J1
        $this->createMatch($manager, $phaseGroupes, $stades['BMO'], new \DateTime('2026-06-15 18:00:00'), 
            $equipes['F'][3], $equipes['F'][2]); // Canada vs Sénégal
        $this->createMatch($manager, $phaseGroupes, $stades['Lincoln'], new \DateTime('2026-06-15 21:00:00'), 
            $equipes['F'][0], $equipes['F'][1]); // Belgique vs Portugal

        // Groupe G - J1
        $this->createMatch($manager, $phaseGroupes, $stades['Lumen'], new \DateTime('2026-06-16 15:00:00'), 
            $equipes['G'][0], $equipes['G'][3]); // Allemagne vs Côte d'Ivoire
        $this->createMatch($manager, $phaseGroupes, $stades['Guadalajara'], new \DateTime('2026-06-16 18:00:00'), 
            $equipes['G'][1], $equipes['G'][2]); // Uruguay vs Colombie

        // Groupe H - J1
        $this->createMatch($manager, $phaseGroupes, $stades['Monterrey'], new \DateTime('2026-06-16 21:00:00'), 
            $equipes['H'][0], $equipes['H'][3]); // Pologne vs Arabie Saoudite
        $this->createMatch($manager, $phaseGroupes, $stades['BC'], new \DateTime('2026-06-17 15:00:00'), 
            $equipes['H'][1], $equipes['H'][2]); // Suède vs Égypte

        // Groupe I - J1
        $this->createMatch($manager, $phaseGroupes, $stades['ATT'], new \DateTime('2026-06-17 18:00:00'), 
            $equipes['I'][0], $equipes['I'][3]); // Serbie vs Iran
        $this->createMatch($manager, $phaseGroupes, $stades['NRG'], new \DateTime('2026-06-17 21:00:00'), 
            $equipes['I'][1], $equipes['I'][2]); // Nigeria vs Tunisie

        // Groupe J - J1
        $this->createMatch($manager, $phaseGroupes, $stades['MetLife'], new \DateTime('2026-06-18 15:00:00'), 
            $equipes['J'][0], $equipes['J'][3]); // Norvège vs Qatar
        $this->createMatch($manager, $phaseGroupes, $stades['SoFi'], new \DateTime('2026-06-18 18:00:00'), 
            $equipes['J'][1], $equipes['J'][2]); // Algérie vs Ghana

        // Groupe K - J1
        $this->createMatch($manager, $phaseGroupes, $stades['MercedesBenz'], new \DateTime('2026-06-18 21:00:00'), 
            $equipes['K'][0], $equipes['K'][3]); // Turquie vs Islande
        $this->createMatch($manager, $phaseGroupes, $stades['Gillette'], new \DateTime('2026-06-19 15:00:00'), 
            $equipes['K'][1], $equipes['K'][2]); // Autriche vs Afrique du Sud

        // Groupe L - J1
        $this->createMatch($manager, $phaseGroupes, $stades['Arrowhead'], new \DateTime('2026-06-19 18:00:00'), 
            $equipes['L'][0], $equipes['L'][3]); // Chili vs Costa Rica
        $this->createMatch($manager, $phaseGroupes, $stades['Lincoln'], new \DateTime('2026-06-19 21:00:00'), 
            $equipes['L'][1], $equipes['L'][2]); // République Tchèque vs Roumanie

        // ===== MATCHS - JOURNÉE 2 =====
        
        // Groupe A - J2
        $this->createMatch($manager, $phaseGroupes, $stades['Guadalajara'], new \DateTime('2026-06-20 15:00:00'), 
            $equipes['A'][2], $equipes['A'][1]); // Japon vs Équateur
        $this->createMatch($manager, $phaseGroupes, $stades['Azteca'], new \DateTime('2026-06-20 21:00:00'), 
            $equipes['A'][0], $equipes['A'][3]); // Mexique vs Australie

        // Groupe B - J2
        $this->createMatch($manager, $phaseGroupes, $stades['ATT'], new \DateTime('2026-06-21 15:00:00'), 
            $equipes['B'][2], $equipes['B'][3]); // Italie vs Nouvelle-Zélande
        $this->createMatch($manager, $phaseGroupes, $stades['SoFi'], new \DateTime('2026-06-21 21:00:00'), 
            $equipes['B'][0], $equipes['B'][1]); // USA vs Pays-Bas

        // Groupe C - J2
        $this->createMatch($manager, $phaseGroupes, $stades['NRG'], new \DateTime('2026-06-22 15:00:00'), 
            $equipes['C'][3], $equipes['C'][1]); // Cameroun vs Danemark
        $this->createMatch($manager, $phaseGroupes, $stades['HardRock'], new \DateTime('2026-06-22 21:00:00'), 
            $equipes['C'][0], $equipes['C'][2]); // Argentine vs Pérou

        // Groupe D - J2
        $this->createMatch($manager, $phaseGroupes, $stades['MercedesBenz'], new \DateTime('2026-06-23 15:00:00'), 
            $equipes['D'][3], $equipes['D'][1]); // Corée du Sud vs Angleterre
        $this->createMatch($manager, $phaseGroupes, $stades['Gillette'], new \DateTime('2026-06-23 21:00:00'), 
            $equipes['D'][0], $equipes['D'][2]); // France vs Suisse

        // Groupe E - J2
        $this->createMatch($manager, $phaseGroupes, $stades['Levi'], new \DateTime('2026-06-24 15:00:00'), 
            $equipes['E'][3], $equipes['E'][1]); // Maroc vs Espagne
        $this->createMatch($manager, $phaseGroupes, $stades['Arrowhead'], new \DateTime('2026-06-24 21:00:00'), 
            $equipes['E'][0], $equipes['E'][2]); // Brésil vs Croatie

        // Groupe F - J2
        $this->createMatch($manager, $phaseGroupes, $stades['Lincoln'], new \DateTime('2026-06-25 15:00:00'), 
            $equipes['F'][2], $equipes['F'][0]); // Sénégal vs Belgique
        $this->createMatch($manager, $phaseGroupes, $stades['BMO'], new \DateTime('2026-06-25 21:00:00'), 
            $equipes['F'][3], $equipes['F'][1]); // Canada vs Portugal

        // Groupe G - J2
        $this->createMatch($manager, $phaseGroupes, $stades['Monterrey'], new \DateTime('2026-06-26 15:00:00'), 
            $equipes['G'][3], $equipes['G'][1]); // Côte d'Ivoire vs Uruguay
        $this->createMatch($manager, $phaseGroupes, $stades['Lumen'], new \DateTime('2026-06-26 21:00:00'), 
            $equipes['G'][0], $equipes['G'][2]); // Allemagne vs Colombie

        // Groupe H - J2
        $this->createMatch($manager, $phaseGroupes, $stades['BC'], new \DateTime('2026-06-27 15:00:00'), 
            $equipes['H'][3], $equipes['H'][1]); // Arabie Saoudite vs Suède
        $this->createMatch($manager, $phaseGroupes, $stades['Guadalajara'], new \DateTime('2026-06-27 21:00:00'), 
            $equipes['H'][0], $equipes['H'][2]); // Pologne vs Égypte

        // Groupe I - J2
        $this->createMatch($manager, $phaseGroupes, $stades['MetLife'], new \DateTime('2026-06-28 15:00:00'), 
            $equipes['I'][3], $equipes['I'][1]); // Iran vs Nigeria
        $this->createMatch($manager, $phaseGroupes, $stades['ATT'], new \DateTime('2026-06-28 21:00:00'), 
            $equipes['I'][0], $equipes['I'][2]); // Serbie vs Tunisie

        // Groupe J - J2
        $this->createMatch($manager, $phaseGroupes, $stades['SoFi'], new \DateTime('2026-06-29 15:00:00'), 
            $equipes['J'][3], $equipes['J'][1]); // Qatar vs Algérie
        $this->createMatch($manager, $phaseGroupes, $stades['NRG'], new \DateTime('2026-06-29 21:00:00'), 
            $equipes['J'][0], $equipes['J'][2]); // Norvège vs Ghana

        // Groupe K - J2
        $this->createMatch($manager, $phaseGroupes, $stades['MercedesBenz'], new \DateTime('2026-06-30 15:00:00'), 
            $equipes['K'][3], $equipes['K'][1]); // Islande vs Autriche
        $this->createMatch($manager, $phaseGroupes, $stades['Gillette'], new \DateTime('2026-06-30 21:00:00'), 
            $equipes['K'][0], $equipes['K'][2]); // Turquie vs Afrique du Sud

        // Groupe L - J2
        $this->createMatch($manager, $phaseGroupes, $stades['Arrowhead'], new \DateTime('2026-07-01 15:00:00'), 
            $equipes['L'][3], $equipes['L'][1]); // Costa Rica vs République Tchèque
        $this->createMatch($manager, $phaseGroupes, $stades['Lincoln'], new \DateTime('2026-07-01 21:00:00'), 
            $equipes['L'][0], $equipes['L'][2]); // Chili vs Roumanie

        // ===== MATCHS - JOURNÉE 3 =====
        
        // Groupe A - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['Monterrey'], new \DateTime('2026-07-02 21:00:00'), 
            $equipes['A'][3], $equipes['A'][2]); // Australie vs Japon
        $this->createMatch($manager, $phaseGroupes, $stades['Azteca'], new \DateTime('2026-07-02 21:00:00'), 
            $equipes['A'][1], $equipes['A'][0]); // Équateur vs Mexique

        // Groupe B - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['MetLife'], new \DateTime('2026-07-03 21:00:00'), 
            $equipes['B'][3], $equipes['B'][1]); // Nouvelle-Zélande vs Pays-Bas
        $this->createMatch($manager, $phaseGroupes, $stades['ATT'], new \DateTime('2026-07-03 21:00:00'), 
            $equipes['B'][2], $equipes['B'][0]); // Italie vs USA

        // Groupe C - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['HardRock'], new \DateTime('2026-07-04 21:00:00'), 
            $equipes['C'][2], $equipes['C'][3]); // Pérou vs Cameroun
        $this->createMatch($manager, $phaseGroupes, $stades['NRG'], new \DateTime('2026-07-04 21:00:00'), 
            $equipes['C'][1], $equipes['C'][0]); // Danemark vs Argentine

        // Groupe D - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['Gillette'], new \DateTime('2026-07-05 21:00:00'), 
            $equipes['D'][2], $equipes['D'][3]); // Suisse vs Corée du Sud
        $this->createMatch($manager, $phaseGroupes, $stades['MercedesBenz'], new \DateTime('2026-07-05 21:00:00'), 
            $equipes['D'][1], $equipes['D'][0]); // Angleterre vs France

        // Groupe E - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['Arrowhead'], new \DateTime('2026-07-06 21:00:00'), 
            $equipes['E'][2], $equipes['E'][3]); // Croatie vs Maroc
        $this->createMatch($manager, $phaseGroupes, $stades['Levi'], new \DateTime('2026-07-06 21:00:00'), 
            $equipes['E'][1], $equipes['E'][0]); // Espagne vs Brésil

        // Groupe F - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['Lincoln'], new \DateTime('2026-07-07 21:00:00'), 
            $equipes['F'][1], $equipes['F'][2]); // Portugal vs Sénégal
        $this->createMatch($manager, $phaseGroupes, $stades['BMO'], new \DateTime('2026-07-07 21:00:00'), 
            $equipes['F'][0], $equipes['F'][3]); // Belgique vs Canada

        // Groupe G - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['Lumen'], new \DateTime('2026-07-08 21:00:00'), 
            $equipes['G'][2], $equipes['G'][3]); // Colombie vs Côte d'Ivoire
        $this->createMatch($manager, $phaseGroupes, $stades['Guadalajara'], new \DateTime('2026-07-08 21:00:00'), 
            $equipes['G'][1], $equipes['G'][0]); // Uruguay vs Allemagne

        // Groupe H - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['BC'], new \DateTime('2026-07-09 21:00:00'), 
            $equipes['H'][2], $equipes['H'][3]); // Égypte vs Arabie Saoudite
        $this->createMatch($manager, $phaseGroupes, $stades['Monterrey'], new \DateTime('2026-07-09 21:00:00'), 
            $equipes['H'][1], $equipes['H'][0]); // Suède vs Pologne

        // Groupe I - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['ATT'], new \DateTime('2026-07-10 21:00:00'), 
            $equipes['I'][2], $equipes['I'][3]); // Tunisie vs Iran
        $this->createMatch($manager, $phaseGroupes, $stades['MetLife'], new \DateTime('2026-07-10 21:00:00'), 
            $equipes['I'][1], $equipes['I'][0]); // Nigeria vs Serbie

        // Groupe J - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['SoFi'], new \DateTime('2026-07-11 21:00:00'), 
            $equipes['J'][2], $equipes['J'][3]); // Ghana vs Qatar
        $this->createMatch($manager, $phaseGroupes, $stades['NRG'], new \DateTime('2026-07-11 21:00:00'), 
            $equipes['J'][1], $equipes['J'][0]); // Algérie vs Norvège

        // Groupe K - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['Gillette'], new \DateTime('2026-07-12 21:00:00'), 
            $equipes['K'][2], $equipes['K'][3]); // Afrique du Sud vs Islande
        $this->createMatch($manager, $phaseGroupes, $stades['MercedesBenz'], new \DateTime('2026-07-12 21:00:00'), 
            $equipes['K'][1], $equipes['K'][0]); // Autriche vs Turquie

        // Groupe L - J3 (matchs simultanés)
        $this->createMatch($manager, $phaseGroupes, $stades['Arrowhead'], new \DateTime('2026-07-13 21:00:00'), 
            $equipes['L'][2], $equipes['L'][3]); // Roumanie vs Costa Rica
        $this->createMatch($manager, $phaseGroupes, $stades['Lincoln'], new \DateTime('2026-07-13 21:00:00'), 
            $equipes['L'][1], $equipes['L'][0]); // République Tchèque vs Chili

        $manager->flush();
    }

    private function createStade(ObjectManager $manager, string $nom, string $ville, string $pays, int $capacite): Stade
    {
        $stade = new Stade();
        $stade->setNom($nom);
        $stade->setVille($ville);
        $stade->setPays($pays);
        $stade->setCapacite($capacite);
        $manager->persist($stade);
        return $stade;
    }

    private function createGroupe(ObjectManager $manager, string $nom, Phase $phase): Groupe
    {
        $groupe = new Groupe();
        $groupe->setNomGroupe($nom);
        $groupe->setPhase($phase);
        $manager->persist($groupe);
        return $groupe;
    }

    private function createEquipe(ObjectManager $manager, string $nom, string $code, Groupe $groupe): Equipe
    {
        $equipe = new Equipe();
        $equipe->setNomEquipe($nom);
        $equipe->setCodePays($code);
        $equipe->setGroupe($groupe);
        $manager->persist($equipe);
        return $equipe;
    }

    private function createMatch(ObjectManager $manager, Phase $phase, Stade $stade, \DateTime $dateHeure, 
                                 Equipe $domicile, Equipe $exterieur): WorldcupMatch
    {
        $match = new WorldcupMatch();
        $match->setDateHeure($dateHeure);
        $match->setStatut('A_VENIR');
        $match->setPhase($phase);
        $match->setStade($stade);
        $manager->persist($match);

        // Participer - Domicile
        $partDom = new Participer();
        $partDom->setMatch($match);
        $partDom->setEquipe($domicile);
        $partDom->setRole('DOMICILE');
        $partDom->setProlongation(false);
        $manager->persist($partDom);

        // Participer - Extérieur
        $partExt = new Participer();
        $partExt->setMatch($match);
        $partExt->setEquipe($exterieur);
        $partExt->setRole('EXTERIEUR');
        $partExt->setProlongation(false);
        $manager->persist($partExt);

        return $match;
    }
}