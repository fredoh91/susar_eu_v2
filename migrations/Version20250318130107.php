<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318130107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_ctll ADD sender VARCHAR(255) NOT NULL, ADD report_type VARCHAR(255) NOT NULL, ADD ev_document_type VARCHAR(255) NOT NULL, ADD country VARCHAR(255) NOT NULL, ADD receive_date DATETIME DEFAULT NULL, ADD receipt_date DATETIME DEFAULT NULL, ADD gateway_date DATETIME DEFAULT NULL, ADD initials_height_weight VARCHAR(255) NOT NULL, ADD age VARCHAR(255) NOT NULL, ADD birth_date VARCHAR(255) NOT NULL, ADD sex VARCHAR(255) NOT NULL, ADD age_group VARCHAR(255) NOT NULL, ADD primary_source_qualification VARCHAR(255) NOT NULL, ADD serious VARCHAR(255) NOT NULL, ADD seriousness_death VARCHAR(255) NOT NULL, ADD seriousness_lifethreatening VARCHAR(255) NOT NULL, ADD seriousness_hospitalisation VARCHAR(255) NOT NULL, ADD seriousness_disabling VARCHAR(255) NOT NULL, ADD seriousness_congenital_anomaly VARCHAR(255) NOT NULL, ADD seriousness_other VARCHAR(255) NOT NULL, ADD parent_child VARCHAR(255) NOT NULL, ADD literature_reference VARCHAR(255) NOT NULL, ADD number_of_literature_reference_documents INT NOT NULL, ADD number_of_documents_held_by_sender INT NOT NULL, ADD recoded_drug_list LONGTEXT NOT NULL, ADD number_of_suspect_interacting_drugs INT NOT NULL, ADD suspect_interacting_enhanced_reported_drug_list LONGTEXT NOT NULL, ADD concomitant_not_administered_enhanced_reported_drug_list LONGTEXT NOT NULL, ADD indications_ptof_the_drug_of_interest_as_reported_in_the_icsr LONGTEXT NOT NULL, ADD positive_rechallenge_for_suspect_interacting_drugs LONGTEXT NOT NULL, ADD reaction_list_pt LONGTEXT NOT NULL, ADD structured_medical_history LONGTEXT NOT NULL, ADD narrative_present VARCHAR(255) NOT NULL, ADD narrative_reporters_comments_and_senders_comments LONGTEXT NOT NULL, ADD icsrform VARCHAR(1000) NOT NULL, ADD e2_b VARCHAR(255) NOT NULL, ADD safety_report_id INT NOT NULL, ADD select_icsr INT NOT NULL, ADD complete_narrative_reporters_comments_and_senders_comments LONGTEXT NOT NULL, ADD case_version INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_ctll DROP sender, DROP report_type, DROP ev_document_type, DROP country, DROP receive_date, DROP receipt_date, DROP gateway_date, DROP initials_height_weight, DROP age, DROP birth_date, DROP sex, DROP age_group, DROP primary_source_qualification, DROP serious, DROP seriousness_death, DROP seriousness_lifethreatening, DROP seriousness_hospitalisation, DROP seriousness_disabling, DROP seriousness_congenital_anomaly, DROP seriousness_other, DROP parent_child, DROP literature_reference, DROP number_of_literature_reference_documents, DROP number_of_documents_held_by_sender, DROP recoded_drug_list, DROP number_of_suspect_interacting_drugs, DROP suspect_interacting_enhanced_reported_drug_list, DROP concomitant_not_administered_enhanced_reported_drug_list, DROP indications_ptof_the_drug_of_interest_as_reported_in_the_icsr, DROP positive_rechallenge_for_suspect_interacting_drugs, DROP reaction_list_pt, DROP structured_medical_history, DROP narrative_present, DROP narrative_reporters_comments_and_senders_comments, DROP icsrform, DROP e2_b, DROP safety_report_id, DROP select_icsr, DROP complete_narrative_reporters_comments_and_senders_comments, DROP case_version');
    }
}
