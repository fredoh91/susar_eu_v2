<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401165534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meddra_md_hierarchy (id INT AUTO_INCREMENT NOT NULL, pt_code BIGINT NOT NULL, hlt_code BIGINT NOT NULL, hlgt_code BIGINT NOT NULL, soc_code BIGINT NOT NULL, pt_name_en VARCHAR(255) NOT NULL, pt_name_fr VARCHAR(255) NOT NULL, hlt_name_en VARCHAR(255) NOT NULL, hlt_name_fr VARCHAR(255) NOT NULL, hlgt_name_en VARCHAR(255) NOT NULL, hlgt_name_fr VARCHAR(255) NOT NULL, soc_name_en VARCHAR(255) NOT NULL, soc_name_fr VARCHAR(255) NOT NULL, soc_abbrev VARCHAR(255) NOT NULL, pt_soc_code BIGINT NOT NULL, primary_soc_fg VARCHAR(5) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE meddra_md_hierarchy');
    }
}
