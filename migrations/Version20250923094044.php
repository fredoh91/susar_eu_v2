<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923094044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_reactionmeddrapt ON effets_indesirables (reactionmeddrapt)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_substancename ON medicaments (substancename)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_priorisation ON susar_eu (priorisation)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_cas_susar_eu_v1 ON susar_eu (cas_susar_eu_v1)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_date_evaluation ON susar_eu (date_evaluation)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_world_wide_id ON susar_eu (world_wide_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_cas_date_eval ON susar_eu (cas_susar_eu_v1, date_evaluation)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX idx_reactionmeddrapt ON effets_indesirables
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_substancename ON medicaments
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_priorisation ON susar_eu
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_cas_susar_eu_v1 ON susar_eu
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_date_evaluation ON susar_eu
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_world_wide_id ON susar_eu
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_cas_date_eval ON susar_eu
        SQL);
    }
}
