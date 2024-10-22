<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017132504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE active_substance_grouping_reprise_donnees (id INT AUTO_INCREMENT NOT NULL, int_sub_dmm_id INT DEFAULT NULL, active_substance_high_level VARCHAR(1000) NOT NULL, active_substance_low_level VARCHAR(1000) NOT NULL, inactif TINYINT(1) DEFAULT NULL, date_fichier DATETIME NOT NULL, utilisateur_import VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7C042B5489BD07FC (int_sub_dmm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE active_substance_grouping_reprise_donnees ADD CONSTRAINT FK_7C042B5489BD07FC FOREIGN KEY (int_sub_dmm_id) REFERENCES intervenant_substance_dmm (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE active_substance_grouping_reprise_donnees DROP FOREIGN KEY FK_7C042B5489BD07FC');
        $this->addSql('DROP TABLE active_substance_grouping_reprise_donnees');
    }
}
