<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320175758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE susar_eu CHANGE master_id master_id INT DEFAULT NULL, CHANGE caseid caseid INT DEFAULT NULL, CHANGE specificcaseid specificcaseid VARCHAR(16) DEFAULT NULL, CHANGE dlpversion dlpversion INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE susar_eu CHANGE master_id master_id INT NOT NULL, CHANGE caseid caseid INT NOT NULL, CHANGE specificcaseid specificcaseid VARCHAR(16) NOT NULL, CHANGE dlpversion dlpversion INT NOT NULL');
    }
}
