<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321111745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE effets_indesirables ADD reaction_list_pt_ctll VARCHAR(1000) DEFAULT NULL, ADD reaction_list_pt VARCHAR(255) DEFAULT NULL, ADD outcome VARCHAR(255) DEFAULT NULL, ADD date VARCHAR(255) DEFAULT NULL, ADD date_format_date DATE DEFAULT NULL, ADD duration VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE indications ADD indication_ctll VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE medicaments ADD produit_suspect VARCHAR(1000) NOT NULL, ADD maladie VARCHAR(255) DEFAULT NULL, ADD statut_medic_apres_effet VARCHAR(255) DEFAULT NULL, ADD date_derniere_admin VARCHAR(255) DEFAULT NULL, ADD date_derniere_admin_format_date DATE DEFAULT NULL, ADD delai_administration_survenue VARCHAR(255) DEFAULT NULL, ADD dosage VARCHAR(255) DEFAULT NULL, ADD voie_admin VARCHAR(255) DEFAULT NULL, ADD comment VARCHAR(255) DEFAULT NULL, ADD type_sa_ms_mono VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE effets_indesirables DROP reaction_list_pt_ctll, DROP reaction_list_pt, DROP outcome, DROP date, DROP date_format_date, DROP duration');
        $this->addSql('ALTER TABLE indications DROP indication_ctll');
        $this->addSql('ALTER TABLE medicaments DROP produit_suspect, DROP maladie, DROP statut_medic_apres_effet, DROP date_derniere_admin, DROP date_derniere_admin_format_date, DROP delai_administration_survenue, DROP dosage, DROP voie_admin, DROP comment, DROP type_sa_ms_mono');
    }
}
