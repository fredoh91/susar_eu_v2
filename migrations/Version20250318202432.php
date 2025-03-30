<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318202432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_ctll CHANGE safety_report_key safety_report_key BIGINT NOT NULL, CHANGE safety_report_id safety_report_id BIGINT NOT NULL, CHANGE select_icsr select_icsr BIGINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_ctll CHANGE safety_report_key safety_report_key INT NOT NULL, CHANGE safety_report_id safety_report_id INT NOT NULL, CHANGE select_icsr select_icsr INT NOT NULL');
    }
}
