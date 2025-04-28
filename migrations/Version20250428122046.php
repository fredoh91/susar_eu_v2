<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428122046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liaisons_intervenant_substance_dmm_v1_v2 DROP FOREIGN KEY liaisons_intervenant_substance_dmm_v1_v2_ibfk_1');
        $this->addSql('DROP TABLE liaisons_intervenant_substance_dmm_v1_v2');
        $this->addSql('ALTER TABLE user ADD password_en_clair VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE liaisons_intervenant_substance_dmm_v1_v2 (intervenant_ansm_id INT DEFAULT NULL, id INT DEFAULT NULL, dmm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, pole_long VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, pole_court VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, evaluateur VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, active_substance_high_level VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, inactif TINYINT(1) DEFAULT NULL, type_sa_ms_mono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', association_de_substances TINYINT(1) DEFAULT NULL, user_create VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, user_modif VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, id_inter_sub_dmm_susar_eu_v1 INT DEFAULT NULL, DCI_EU_v1 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, Type_saMS_Mono_v1 VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, inactif_v1 VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, evaluateur_v1 VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, NbLigne_v1 INT DEFAULT NULL, INDEX IDX_C6DC9208257166FF (intervenant_ansm_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE liaisons_intervenant_substance_dmm_v1_v2 ADD CONSTRAINT liaisons_intervenant_substance_dmm_v1_v2_ibfk_1 FOREIGN KEY (intervenant_ansm_id) REFERENCES intervenants_ansm (id)');
        $this->addSql('ALTER TABLE user DROP password_en_clair');
    }
}
