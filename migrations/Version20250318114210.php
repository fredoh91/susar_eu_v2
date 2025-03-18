<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318114210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE import_ctll (id INT AUTO_INCREMENT NOT NULL, import_cttl_fic_excel_id INT DEFAULT NULL, safety_report_key INT NOT NULL, study_registration_n VARCHAR(1000) NOT NULL, sponsor_study_number VARCHAR(255) NOT NULL, ev_safety_report_identifier VARCHAR(255) NOT NULL, case_report_number VARCHAR(1000) NOT NULL, INDEX IDX_3B4FA025A2629E54 (import_cttl_fic_excel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE import_cttl_fic_excel (id INT AUTO_INCREMENT NOT NULL, date_fichier DATETIME NOT NULL, utilisateur_import VARCHAR(255) DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, date_import DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE import_ctll ADD CONSTRAINT FK_3B4FA025A2629E54 FOREIGN KEY (import_cttl_fic_excel_id) REFERENCES import_cttl_fic_excel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_ctll DROP FOREIGN KEY FK_3B4FA025A2629E54');
        $this->addSql('DROP TABLE import_ctll');
        $this->addSql('DROP TABLE import_cttl_fic_excel');
    }
}
