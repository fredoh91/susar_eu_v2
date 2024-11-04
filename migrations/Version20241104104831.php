<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104104831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lien_evaluation_bnpv (id INT AUTO_INCREMENT NOT NULL, id_ctll INT DEFAULT NULL, id_produit_pt_eval INT DEFAULT NULL, id_produit INT DEFAULT NULL, dci_eu VARCHAR(255) DEFAULT NULL, susar_eu_active_substance_high_level VARCHAR(255) DEFAULT NULL, substance_susar_eu_v1 VARCHAR(255) DEFAULT NULL, id_produit_pt INT DEFAULT NULL, changes VARCHAR(255) DEFAULT NULL, assessment_outcome VARCHAR(255) DEFAULT NULL, comments VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, date_crea DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', utilisateur_crea VARCHAR(255) DEFAULT NULL, date_modif DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', utilisateur_modif VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lien_evaluation_bnpv');
    }
}
