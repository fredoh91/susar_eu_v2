<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319110546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_ctll DROP FOREIGN KEY FK_3B4FA025A2629E54');
        $this->addSql('CREATE TABLE import_ctll_fic_excel (id INT AUTO_INCREMENT NOT NULL, date_fichier DATETIME NOT NULL, utilisateur_import VARCHAR(255) DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, date_import DATETIME DEFAULT NULL, nb_lignes_data_fic_excel INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE import_cttl_fic_excel');
        $this->addSql('DROP INDEX IDX_3B4FA025A2629E54 ON import_ctll');
        $this->addSql('ALTER TABLE import_ctll CHANGE import_cttl_fic_excel_id import_ctll_fic_excel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE import_ctll ADD CONSTRAINT FK_3B4FA0252989CD9C FOREIGN KEY (import_ctll_fic_excel_id) REFERENCES import_ctll_fic_excel (id)');
        $this->addSql('CREATE INDEX IDX_3B4FA0252989CD9C ON import_ctll (import_ctll_fic_excel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_ctll DROP FOREIGN KEY FK_3B4FA0252989CD9C');
        $this->addSql('CREATE TABLE import_cttl_fic_excel (id INT AUTO_INCREMENT NOT NULL, date_fichier DATETIME NOT NULL, utilisateur_import VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, file_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_import DATETIME DEFAULT NULL, nb_lignes_data_fic_excel INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE import_ctll_fic_excel');
        $this->addSql('DROP INDEX IDX_3B4FA0252989CD9C ON import_ctll');
        $this->addSql('ALTER TABLE import_ctll CHANGE import_ctll_fic_excel_id import_cttl_fic_excel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE import_ctll ADD CONSTRAINT FK_3B4FA025A2629E54 FOREIGN KEY (import_cttl_fic_excel_id) REFERENCES import_cttl_fic_excel (id)');
        $this->addSql('CREATE INDEX IDX_3B4FA025A2629E54 ON import_ctll (import_cttl_fic_excel_id)');
    }
}
