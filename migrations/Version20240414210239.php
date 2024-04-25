<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414210239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenant_substance_dmm ADD act_sub_grouping_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervenant_substance_dmm ADD CONSTRAINT FK_C6DC920855D0D4DC FOREIGN KEY (act_sub_grouping_id) REFERENCES active_substance_grouping (id)');
        $this->addSql('CREATE INDEX IDX_C6DC920855D0D4DC ON intervenant_substance_dmm (act_sub_grouping_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenant_substance_dmm DROP FOREIGN KEY FK_C6DC920855D0D4DC');
        $this->addSql('DROP INDEX IDX_C6DC920855D0D4DC ON intervenant_substance_dmm');
        $this->addSql('ALTER TABLE intervenant_substance_dmm DROP act_sub_grouping_id');
    }
}
