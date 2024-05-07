<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501102729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicaments ADD intervenant_substance_dmm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medicaments ADD CONSTRAINT FK_DD988ACBA440E229 FOREIGN KEY (intervenant_substance_dmm_id) REFERENCES intervenant_substance_dmm (id)');
        $this->addSql('CREATE INDEX IDX_DD988ACBA440E229 ON medicaments (intervenant_substance_dmm_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicaments DROP FOREIGN KEY FK_DD988ACBA440E229');
        $this->addSql('DROP INDEX IDX_DD988ACBA440E229 ON medicaments');
        $this->addSql('ALTER TABLE medicaments DROP intervenant_substance_dmm_id');
    }
}
