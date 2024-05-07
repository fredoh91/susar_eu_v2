<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426123557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE active_substance_grouping ADD int_sub_dmm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE active_substance_grouping ADD CONSTRAINT FK_2F7923B189BD07FC FOREIGN KEY (int_sub_dmm_id) REFERENCES intervenant_substance_dmm (id)');
        $this->addSql('CREATE INDEX IDX_2F7923B189BD07FC ON active_substance_grouping (int_sub_dmm_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE active_substance_grouping DROP FOREIGN KEY FK_2F7923B189BD07FC');
        $this->addSql('DROP INDEX IDX_2F7923B189BD07FC ON active_substance_grouping');
        $this->addSql('ALTER TABLE active_substance_grouping DROP int_sub_dmm_id');
    }
}
