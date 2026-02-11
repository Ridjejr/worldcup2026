<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260210143433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // On ajoute les colonnes avec une valeur par défaut à 0 pour éviter les NULL sur les équipes existantes
        // $this->addSql('ALTER TABLE Equipe ADD points INT DEFAULT 0');
        // $this->addSql('ALTER TABLE Equipe ADD joues INT DEFAULT 0');
        // $this->addSql('ALTER TABLE Equipe ADD gagnes INT DEFAULT 0');
        // $this->addSql('ALTER TABLE Equipe ADD nuls INT DEFAULT 0');
        // $this->addSql('ALTER TABLE Equipe ADD perdus INT DEFAULT 0');
        // // Attention : Doctrine convertit généralement le camelCase en snake_case
        // $this->addSql('ALTER TABLE Equipe ADD buts_pour INT DEFAULT 0');
        // $this->addSql('ALTER TABLE Equipe ADD buts_contre INT DEFAULT 0');
        // $this->addSql('ALTER TABLE Equipe ADD diff_buts INT DEFAULT 0');
        // $this->addSql('ALTER TABLE Equipe ADD rang INT DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // En cas de rollback, on supprime les colonnes
        // $this->addSql('ALTER TABLE Equipe DROP points');
        // $this->addSql('ALTER TABLE Equipe DROP joues');
        // $this->addSql('ALTER TABLE Equipe DROP gagnes');
        // $this->addSql('ALTER TABLE Equipe DROP nuls');
        // $this->addSql('ALTER TABLE Equipe DROP perdus');
        // $this->addSql('ALTER TABLE Equipe DROP buts_pour');
        // $this->addSql('ALTER TABLE Equipe DROP buts_contre');
        // $this->addSql('ALTER TABLE Equipe DROP diff_buts');
        // $this->addSql('ALTER TABLE Equipe DROP rang');
    }
}
