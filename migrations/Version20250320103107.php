<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320103107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE susar_eu ADD import_ctll_id INT DEFAULT NULL, ADD ev_safety_report_identifier VARCHAR(255) NOT NULL, ADD study_registration_n VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE susar_eu ADD CONSTRAINT FK_4E7A8CCF10A3A1E8 FOREIGN KEY (import_ctll_id) REFERENCES import_ctll (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4E7A8CCF10A3A1E8 ON susar_eu (import_ctll_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE susar_eu DROP FOREIGN KEY FK_4E7A8CCF10A3A1E8');
        $this->addSql('DROP INDEX UNIQ_4E7A8CCF10A3A1E8 ON susar_eu');
        $this->addSql('ALTER TABLE susar_eu DROP import_ctll_id, DROP ev_safety_report_identifier, DROP study_registration_n');
    }
}
