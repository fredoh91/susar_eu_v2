<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250505171400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX idx_active_substance_pt ON substance_pt (active_substance_high_level(191), reactionmeddrapt(191))');
        $this->addSql('CREATE INDEX idx_ev_safety_report_identifier ON susar_eu (ev_safety_report_identifier)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_active_substance_pt ON substance_pt');
        $this->addSql('DROP INDEX idx_ev_safety_report_identifier ON susar_eu');
    }
}
