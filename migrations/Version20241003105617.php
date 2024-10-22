<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241003105617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lien_ctll_bnpv (id INT AUTO_INCREMENT NOT NULL, id_ctll INT NOT NULL, ev_safety_report_identifier VARCHAR(255) DEFAULT NULL, case_report_number VARCHAR(255) DEFAULT NULL, case_version INT DEFAULT NULL, study_registration_number VARCHAR(255) DEFAULT NULL, receive_date DATETIME DEFAULT NULL, receipt_date DATETIME DEFAULT NULL, gateway_date DATETIME DEFAULT NULL, master_id INT DEFAULT NULL, caseid INT DEFAULT NULL, specificcaseid VARCHAR(255) DEFAULT NULL, dlpversion INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lien_ctll_bnpv');
    }
}
