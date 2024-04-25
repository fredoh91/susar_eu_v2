<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415220219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenant_substance_dmm ADD intervenant_ansm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervenant_substance_dmm ADD CONSTRAINT FK_C6DC9208257166FF FOREIGN KEY (intervenant_ansm_id) REFERENCES intervenants_ansm (id)');
        $this->addSql('CREATE INDEX IDX_C6DC9208257166FF ON intervenant_substance_dmm (intervenant_ansm_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenant_substance_dmm DROP FOREIGN KEY FK_C6DC9208257166FF');
        $this->addSql('DROP INDEX IDX_C6DC9208257166FF ON intervenant_substance_dmm');
        $this->addSql('ALTER TABLE intervenant_substance_dmm DROP intervenant_ansm_id');
    }
}
