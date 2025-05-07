<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506165223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE liaison_intervenant_substance_dmm_v1_v2 (id INT AUTO_INCREMENT NOT NULL, id_intervenant_substance_dmm INT NOT NULL, dmm VARCHAR(255) DEFAULT NULL, pole_long VARCHAR(255) DEFAULT NULL, pole_court VARCHAR(255) DEFAULT NULL, evaluateur VARCHAR(255) DEFAULT NULL, active_substance_high_level VARCHAR(1000) DEFAULT NULL, inactif TINYINT(1) DEFAULT NULL, type_sa_msmono VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', association_de_substances TINYINT(1) DEFAULT NULL, user_create VARCHAR(255) DEFAULT NULL, user_modif VARCHAR(255) DEFAULT NULL, id_inter_sub_dmmsusar_euv1 INT DEFAULT NULL, dci_eu_v1 VARCHAR(1000) DEFAULT NULL, type_sa_ms_mono_v1 VARCHAR(255) DEFAULT NULL, inactif_v1 TINYINT(1) DEFAULT NULL, evaluateur_v1 VARCHAR(255) DEFAULT NULL, nb_ligne_v1 INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE liaison_intervenant_substance_dmm_v1_v2');
    }
}
