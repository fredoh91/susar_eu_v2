<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320180600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intervenant_substance_dmm_susar_eu (intervenant_substance_dmm_id INT NOT NULL, susar_eu_id INT NOT NULL, INDEX IDX_1D154CA3A440E229 (intervenant_substance_dmm_id), INDEX IDX_1D154CA3DB545B66 (susar_eu_id), PRIMARY KEY(intervenant_substance_dmm_id, susar_eu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt (id INT AUTO_INCREMENT NOT NULL, active_substance_high_level VARCHAR(1000) NOT NULL, codereactionmeddrapt INT NOT NULL, reactionmeddrapt VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt_susar_eu (substance_pt_id INT NOT NULL, susar_eu_id INT NOT NULL, INDEX IDX_CF2D67838987F31C (substance_pt_id), INDEX IDX_CF2D6783DB545B66 (susar_eu_id), PRIMARY KEY(substance_pt_id, susar_eu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt_eval (id INT AUTO_INCREMENT NOT NULL, changes VARCHAR(255) DEFAULT NULL, assessment_outcome VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, date_eval DATE DEFAULT NULL, user_create VARCHAR(255) DEFAULT NULL, user_modif VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt_eval_substance_pt (substance_pt_eval_id INT NOT NULL, substance_pt_id INT NOT NULL, INDEX IDX_5EA3B4E23CC6D950 (substance_pt_eval_id), INDEX IDX_5EA3B4E28987F31C (substance_pt_id), PRIMARY KEY(substance_pt_eval_id, substance_pt_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt_eval_susar_eu (substance_pt_eval_id INT NOT NULL, susar_eu_id INT NOT NULL, INDEX IDX_DCFB99923CC6D950 (substance_pt_eval_id), INDEX IDX_DCFB9992DB545B66 (susar_eu_id), PRIMARY KEY(substance_pt_eval_id, susar_eu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intervenant_substance_dmm_susar_eu ADD CONSTRAINT FK_1D154CA3A440E229 FOREIGN KEY (intervenant_substance_dmm_id) REFERENCES intervenant_substance_dmm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervenant_substance_dmm_susar_eu ADD CONSTRAINT FK_1D154CA3DB545B66 FOREIGN KEY (susar_eu_id) REFERENCES susar_eu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE substance_pt_susar_eu ADD CONSTRAINT FK_CF2D67838987F31C FOREIGN KEY (substance_pt_id) REFERENCES substance_pt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE substance_pt_susar_eu ADD CONSTRAINT FK_CF2D6783DB545B66 FOREIGN KEY (susar_eu_id) REFERENCES susar_eu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE substance_pt_eval_substance_pt ADD CONSTRAINT FK_5EA3B4E23CC6D950 FOREIGN KEY (substance_pt_eval_id) REFERENCES substance_pt_eval (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE substance_pt_eval_substance_pt ADD CONSTRAINT FK_5EA3B4E28987F31C FOREIGN KEY (substance_pt_id) REFERENCES substance_pt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE substance_pt_eval_susar_eu ADD CONSTRAINT FK_DCFB99923CC6D950 FOREIGN KEY (substance_pt_eval_id) REFERENCES substance_pt_eval (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE substance_pt_eval_susar_eu ADD CONSTRAINT FK_DCFB9992DB545B66 FOREIGN KEY (susar_eu_id) REFERENCES susar_eu (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenant_substance_dmm_susar_eu DROP FOREIGN KEY FK_1D154CA3A440E229');
        $this->addSql('ALTER TABLE intervenant_substance_dmm_susar_eu DROP FOREIGN KEY FK_1D154CA3DB545B66');
        $this->addSql('ALTER TABLE substance_pt_susar_eu DROP FOREIGN KEY FK_CF2D67838987F31C');
        $this->addSql('ALTER TABLE substance_pt_susar_eu DROP FOREIGN KEY FK_CF2D6783DB545B66');
        $this->addSql('ALTER TABLE substance_pt_eval_substance_pt DROP FOREIGN KEY FK_5EA3B4E23CC6D950');
        $this->addSql('ALTER TABLE substance_pt_eval_substance_pt DROP FOREIGN KEY FK_5EA3B4E28987F31C');
        $this->addSql('ALTER TABLE substance_pt_eval_susar_eu DROP FOREIGN KEY FK_DCFB99923CC6D950');
        $this->addSql('ALTER TABLE substance_pt_eval_susar_eu DROP FOREIGN KEY FK_DCFB9992DB545B66');
        $this->addSql('DROP TABLE intervenant_substance_dmm_susar_eu');
        $this->addSql('DROP TABLE substance_pt');
        $this->addSql('DROP TABLE substance_pt_susar_eu');
        $this->addSql('DROP TABLE substance_pt_eval');
        $this->addSql('DROP TABLE substance_pt_eval_substance_pt');
        $this->addSql('DROP TABLE substance_pt_eval_susar_eu');
    }
}
