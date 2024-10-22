<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015160511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lien_produit_bnpv (id INT AUTO_INCREMENT NOT NULL, id_ctll INT DEFAULT NULL, id_produit INT DEFAULT NULL, id_inter_sub_dmm INT DEFAULT NULL, inter_sub_dmm_substance VARCHAR(255) DEFAULT NULL, id_susar_eu_int_sub_dmm INT DEFAULT NULL, susar_eu_active_substance_high_level VARCHAR(255) DEFAULT NULL, substance_susar_eu_v1 VARCHAR(255) DEFAULT NULL, association_de_substances TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lien_produit_bnpv');
    }
}
