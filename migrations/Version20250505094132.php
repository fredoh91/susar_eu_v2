<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250505094132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_ctll_fic_excel ADD nb_inserted_susar INT DEFAULT NULL, ADD nb_inserted_medic INT DEFAULT NULL, ADD nb_inserted_eff_ind INT DEFAULT NULL, ADD nb_inserted_med_hist INT DEFAULT NULL, ADD nb_inserted_indic INT DEFAULT NULL, ADD nb_susar_attribue INT DEFAULT NULL, ADD nb_medic_attribue INT DEFAULT NULL, CHANGE nb_lignes_data_fic_excel nb_lignes_data_fic_excel INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_ctll_fic_excel DROP nb_inserted_susar, DROP nb_inserted_medic, DROP nb_inserted_eff_ind, DROP nb_inserted_med_hist, DROP nb_inserted_indic, DROP nb_susar_attribue, DROP nb_medic_attribue, CHANGE nb_lignes_data_fic_excel nb_lignes_data_fic_excel INT NOT NULL');
    }
}
