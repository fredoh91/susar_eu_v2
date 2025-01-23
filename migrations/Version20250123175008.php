<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123175008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE active_substance_grouping_fic_excel (id INT AUTO_INCREMENT NOT NULL, date_fichier DATETIME DEFAULT NULL, utilisateur_import VARCHAR(255) DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, date_import DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE active_substance_grouping ADD fic_excel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE active_substance_grouping ADD CONSTRAINT FK_2F7923B170D6772D FOREIGN KEY (fic_excel_id) REFERENCES active_substance_grouping_fic_excel (id)');
        $this->addSql('CREATE INDEX IDX_2F7923B170D6772D ON active_substance_grouping (fic_excel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE active_substance_grouping DROP FOREIGN KEY FK_2F7923B170D6772D');
        $this->addSql('DROP TABLE active_substance_grouping_fic_excel');
        $this->addSql('DROP INDEX IDX_2F7923B170D6772D ON active_substance_grouping');
        $this->addSql('ALTER TABLE active_substance_grouping DROP fic_excel_id');
    }
}
