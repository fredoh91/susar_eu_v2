<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530213243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dme (id INT AUTO_INCREMENT NOT NULL, llt_code INT DEFAULT NULL, llt_name_en VARCHAR(255) DEFAULT NULL, llt_name_fr VARCHAR(255) DEFAULT NULL, pt_code INT DEFAULT NULL, llt_currency VARCHAR(255) DEFAULT NULL, inactif TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ime (id INT AUTO_INCREMENT NOT NULL, llt_code INT DEFAULT NULL, llt_name_en VARCHAR(255) DEFAULT NULL, llt_name_fr VARCHAR(255) DEFAULT NULL, pt_code INT DEFAULT NULL, llt_currency VARCHAR(255) DEFAULT NULL, inactif TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays_europe (id INT AUTO_INCREMENT NOT NULL, lib_pays VARCHAR(255) NOT NULL, code_pays VARCHAR(255) DEFAULT NULL, inactif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dme');
        $this->addSql('DROP TABLE ime');
        $this->addSql('DROP TABLE pays_europe');
    }
}
