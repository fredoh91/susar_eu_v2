<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321100014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE effets_indesirables CHANGE master_id master_id INT DEFAULT NULL, CHANGE caseid caseid INT DEFAULT NULL, CHANGE specificcaseid specificcaseid VARCHAR(255) DEFAULT NULL, CHANGE dlpversion dlpversion INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medicaments CHANGE caseid caseid INT DEFAULT NULL, CHANGE specificcaseid specificcaseid VARCHAR(255) DEFAULT NULL, CHANGE dlpversion dlpversion INT DEFAULT NULL, CHANGE productcharacterization productcharacterization VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE effets_indesirables CHANGE master_id master_id INT NOT NULL, CHANGE caseid caseid INT NOT NULL, CHANGE specificcaseid specificcaseid VARCHAR(255) NOT NULL, CHANGE dlpversion dlpversion INT NOT NULL');
        $this->addSql('ALTER TABLE medicaments CHANGE caseid caseid INT NOT NULL, CHANGE specificcaseid specificcaseid VARCHAR(255) NOT NULL, CHANGE dlpversion dlpversion INT NOT NULL, CHANGE productcharacterization productcharacterization VARCHAR(255) NOT NULL');
    }
}
