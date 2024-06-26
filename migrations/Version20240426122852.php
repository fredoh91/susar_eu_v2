<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426122852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE active_substance_grouping (id INT AUTO_INCREMENT NOT NULL, active_substance_high_level VARCHAR(1000) NOT NULL, active_substance_low_level VARCHAR(1000) NOT NULL, inactif TINYINT(1) DEFAULT NULL, date_fichier DATETIME NOT NULL, utilisateur_import VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE effets_indesirables (id INT AUTO_INCREMENT NOT NULL, susar_id INT DEFAULT NULL, master_id INT NOT NULL, caseid INT NOT NULL, specificcaseid VARCHAR(255) NOT NULL, dlpversion INT NOT NULL, reactionstartdate DATETIME DEFAULT NULL, reactionmeddrallt VARCHAR(255) DEFAULT NULL, codereactionmeddrallt INT DEFAULT NULL, reactionmeddrapt VARCHAR(255) DEFAULT NULL, codereactionmeddrapt INT DEFAULT NULL, reactionmeddrahlt VARCHAR(255) DEFAULT NULL, codereactionmeddrahlt INT DEFAULT NULL, reactionmeddrahlgt VARCHAR(255) DEFAULT NULL, codereactionmeddrahlgt INT DEFAULT NULL, soc VARCHAR(255) DEFAULT NULL, reactionmeddrasoc INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_63288522887FE331 (susar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indications (id INT AUTO_INCREMENT NOT NULL, susar_id INT DEFAULT NULL, product_name VARCHAR(255) DEFAULT NULL, product_indications VARCHAR(255) DEFAULT NULL, product_indications_eng VARCHAR(255) DEFAULT NULL, code_product_indications INT DEFAULT NULL, productcharacterization VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', master_id INT DEFAULT NULL, INDEX IDX_368D819887FE331 (susar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervenant_substance_dmm (id INT AUTO_INCREMENT NOT NULL, intervenant_ansm_id INT DEFAULT NULL, dmm VARCHAR(255) DEFAULT NULL, pole_long VARCHAR(255) DEFAULT NULL, pole_court VARCHAR(255) DEFAULT NULL, evaluateur VARCHAR(255) DEFAULT NULL, active_substance_high_level VARCHAR(1000) DEFAULT NULL, inactif TINYINT(1) DEFAULT NULL, type_sa_ms_mono VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', association_de_substances TINYINT(1) DEFAULT NULL, INDEX IDX_C6DC9208257166FF (intervenant_ansm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervenant_substance_dmm_susar_eu (intervenant_substance_dmm_id INT NOT NULL, susar_eu_id INT NOT NULL, INDEX IDX_1D154CA3A440E229 (intervenant_substance_dmm_id), INDEX IDX_1D154CA3DB545B66 (susar_eu_id), PRIMARY KEY(intervenant_substance_dmm_id, susar_eu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervenant_substance_dmmsubstance (id INT AUTO_INCREMENT NOT NULL, intervenant_substance_dmm_id INT DEFAULT NULL, active_substance_high_level VARCHAR(1000) NOT NULL, inactif TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', active_substance_low_level VARCHAR(1000) DEFAULT NULL, INDEX IDX_992E4E15A440E229 (intervenant_substance_dmm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervenants_ansm (id INT AUTO_INCREMENT NOT NULL, dmm VARCHAR(255) DEFAULT NULL, pole_court VARCHAR(255) DEFAULT NULL, pole_long VARCHAR(255) DEFAULT NULL, inactif TINYINT(1) NOT NULL, ordre_tri SMALLINT NOT NULL, dmm_pole_court VARCHAR(255) DEFAULT NULL, dmm_pole_long VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medical_history (id INT AUTO_INCREMENT NOT NULL, susar_id INT DEFAULT NULL, disease_lib_llt VARCHAR(255) DEFAULT NULL, disease_lib_pt VARCHAR(255) DEFAULT NULL, disease_code_llt INT DEFAULT NULL, disease_code_pt INT DEFAULT NULL, continuing VARCHAR(255) DEFAULT NULL, medicalcomment LONGTEXT DEFAULT NULL, master_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_61B89085887FE331 (susar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicaments (id INT AUTO_INCREMENT NOT NULL, susar_id INT DEFAULT NULL, master_id INT NOT NULL, caseid INT NOT NULL, specificcaseid VARCHAR(255) NOT NULL, dlpversion INT NOT NULL, productcharacterization VARCHAR(255) NOT NULL, productname VARCHAR(255) DEFAULT NULL, nbblock INT DEFAULT NULL, substancename VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', nbblock2 INT DEFAULT NULL, active_substance_high_level VARCHAR(1000) DEFAULT NULL, INDEX IDX_DD988ACB887FE331 (susar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt (id INT AUTO_INCREMENT NOT NULL, active_substance_high_level VARCHAR(1000) NOT NULL, codereactionmeddrapt INT NOT NULL, reactionmeddrapt VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt_susar_eu (substance_pt_id INT NOT NULL, susar_eu_id INT NOT NULL, INDEX IDX_CF2D67838987F31C (substance_pt_id), INDEX IDX_CF2D6783DB545B66 (susar_eu_id), PRIMARY KEY(substance_pt_id, susar_eu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt_eval (id INT AUTO_INCREMENT NOT NULL, changes VARCHAR(255) DEFAULT NULL, assessment_outcome VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, date_eval DATE DEFAULT NULL, user_create VARCHAR(255) DEFAULT NULL, user_modif VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt_eval_substance_pt (substance_pt_eval_id INT NOT NULL, substance_pt_id INT NOT NULL, INDEX IDX_5EA3B4E23CC6D950 (substance_pt_eval_id), INDEX IDX_5EA3B4E28987F31C (substance_pt_id), PRIMARY KEY(substance_pt_eval_id, substance_pt_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance_pt_eval_susar_eu (substance_pt_eval_id INT NOT NULL, susar_eu_id INT NOT NULL, INDEX IDX_DCFB99923CC6D950 (substance_pt_eval_id), INDEX IDX_DCFB9992DB545B66 (susar_eu_id), PRIMARY KEY(substance_pt_eval_id, susar_eu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE susar_eu (id INT AUTO_INCREMENT NOT NULL, master_id INT NOT NULL, caseid INT NOT NULL, specificcaseid VARCHAR(16) NOT NULL, dlpversion INT NOT NULL, creationdate DATETIME DEFAULT NULL, statusdate DATETIME DEFAULT NULL, studytitle LONGTEXT DEFAULT NULL, sponsorstudynumb VARCHAR(50) DEFAULT NULL, num_eudract VARCHAR(255) DEFAULT NULL, pays_etude VARCHAR(255) DEFAULT NULL, type_susar VARCHAR(255) DEFAULT NULL, indication LONGTEXT DEFAULT NULL, indication_eng VARCHAR(255) DEFAULT NULL, product_name VARCHAR(255) DEFAULT NULL, substance_name VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, date_evaluation DATETIME DEFAULT NULL, narratif LONGTEXT DEFAULT NULL, pays_survenue VARCHAR(255) DEFAULT NULL, date_aiguillage DATETIME DEFAULT NULL, date_import DATETIME DEFAULT NULL, nb_medic_suspect INT DEFAULT NULL, patient_age_group VARCHAR(255) DEFAULT NULL, patient_age DOUBLE PRECISION DEFAULT NULL, patient_age_unit_label VARCHAR(255) DEFAULT NULL, is_case_serious VARCHAR(255) DEFAULT NULL, seriousness_criteria VARCHAR(255) DEFAULT NULL, patient_sex VARCHAR(255) DEFAULT NULL, world_wide_id VARCHAR(255) DEFAULT NULL, seriousness_criteria_brut LONGTEXT DEFAULT NULL, utilisateur_evaluation VARCHAR(255) DEFAULT NULL, utilisateur_aiguillage VARCHAR(255) DEFAULT NULL, utilisateur_import VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE effets_indesirables ADD CONSTRAINT FK_63288522887FE331 FOREIGN KEY (susar_id) REFERENCES susar_eu (id)');
        $this->addSql('ALTER TABLE indications ADD CONSTRAINT FK_368D819887FE331 FOREIGN KEY (susar_id) REFERENCES susar_eu (id)');
        $this->addSql('ALTER TABLE intervenant_substance_dmm ADD CONSTRAINT FK_C6DC9208257166FF FOREIGN KEY (intervenant_ansm_id) REFERENCES intervenants_ansm (id)');
        $this->addSql('ALTER TABLE intervenant_substance_dmm_susar_eu ADD CONSTRAINT FK_1D154CA3A440E229 FOREIGN KEY (intervenant_substance_dmm_id) REFERENCES intervenant_substance_dmm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervenant_substance_dmm_susar_eu ADD CONSTRAINT FK_1D154CA3DB545B66 FOREIGN KEY (susar_eu_id) REFERENCES susar_eu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervenant_substance_dmmsubstance ADD CONSTRAINT FK_992E4E15A440E229 FOREIGN KEY (intervenant_substance_dmm_id) REFERENCES intervenant_substance_dmm (id)');
        $this->addSql('ALTER TABLE medical_history ADD CONSTRAINT FK_61B89085887FE331 FOREIGN KEY (susar_id) REFERENCES susar_eu (id)');
        $this->addSql('ALTER TABLE medicaments ADD CONSTRAINT FK_DD988ACB887FE331 FOREIGN KEY (susar_id) REFERENCES susar_eu (id)');
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
        $this->addSql('ALTER TABLE effets_indesirables DROP FOREIGN KEY FK_63288522887FE331');
        $this->addSql('ALTER TABLE indications DROP FOREIGN KEY FK_368D819887FE331');
        $this->addSql('ALTER TABLE intervenant_substance_dmm DROP FOREIGN KEY FK_C6DC9208257166FF');
        $this->addSql('ALTER TABLE intervenant_substance_dmm_susar_eu DROP FOREIGN KEY FK_1D154CA3A440E229');
        $this->addSql('ALTER TABLE intervenant_substance_dmm_susar_eu DROP FOREIGN KEY FK_1D154CA3DB545B66');
        $this->addSql('ALTER TABLE intervenant_substance_dmmsubstance DROP FOREIGN KEY FK_992E4E15A440E229');
        $this->addSql('ALTER TABLE medical_history DROP FOREIGN KEY FK_61B89085887FE331');
        $this->addSql('ALTER TABLE medicaments DROP FOREIGN KEY FK_DD988ACB887FE331');
        $this->addSql('ALTER TABLE substance_pt_susar_eu DROP FOREIGN KEY FK_CF2D67838987F31C');
        $this->addSql('ALTER TABLE substance_pt_susar_eu DROP FOREIGN KEY FK_CF2D6783DB545B66');
        $this->addSql('ALTER TABLE substance_pt_eval_substance_pt DROP FOREIGN KEY FK_5EA3B4E23CC6D950');
        $this->addSql('ALTER TABLE substance_pt_eval_substance_pt DROP FOREIGN KEY FK_5EA3B4E28987F31C');
        $this->addSql('ALTER TABLE substance_pt_eval_susar_eu DROP FOREIGN KEY FK_DCFB99923CC6D950');
        $this->addSql('ALTER TABLE substance_pt_eval_susar_eu DROP FOREIGN KEY FK_DCFB9992DB545B66');
        $this->addSql('DROP TABLE active_substance_grouping');
        $this->addSql('DROP TABLE effets_indesirables');
        $this->addSql('DROP TABLE indications');
        $this->addSql('DROP TABLE intervenant_substance_dmm');
        $this->addSql('DROP TABLE intervenant_substance_dmm_susar_eu');
        $this->addSql('DROP TABLE intervenant_substance_dmmsubstance');
        $this->addSql('DROP TABLE intervenants_ansm');
        $this->addSql('DROP TABLE medical_history');
        $this->addSql('DROP TABLE medicaments');
        $this->addSql('DROP TABLE substance_pt');
        $this->addSql('DROP TABLE substance_pt_susar_eu');
        $this->addSql('DROP TABLE substance_pt_eval');
        $this->addSql('DROP TABLE substance_pt_eval_substance_pt');
        $this->addSql('DROP TABLE substance_pt_eval_susar_eu');
        $this->addSql('DROP TABLE susar_eu');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
