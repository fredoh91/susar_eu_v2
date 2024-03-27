<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325131526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intervenant_substance_dmmsubstance (id INT AUTO_INCREMENT NOT NULL, intervenant_substance_dmm_id INT DEFAULT NULL, active_substance_high_level VARCHAR(1000) NOT NULL, inactif TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_992E4E15A440E229 (intervenant_substance_dmm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intervenant_substance_dmmsubstance ADD CONSTRAINT FK_992E4E15A440E229 FOREIGN KEY (intervenant_substance_dmm_id) REFERENCES intervenant_substance_dmm (id)');
        $this->addSql('ALTER TABLE intervenant_substance_dmm ADD association_de_substances TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenant_substance_dmmsubstance DROP FOREIGN KEY FK_992E4E15A440E229');
        $this->addSql('DROP TABLE intervenant_substance_dmmsubstance');
        $this->addSql('ALTER TABLE intervenant_substance_dmm DROP association_de_substances');
    }
}
