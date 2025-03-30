<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328211047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medical_history ADD medical_history_ctll VARCHAR(1000) DEFAULT NULL, ADD disease VARCHAR(255) DEFAULT NULL, ADD comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE susar_eu ADD receive_date DATETIME DEFAULT NULL, ADD receipt_date DATETIME DEFAULT NULL, ADD gateway_date DATETIME DEFAULT NULL, ADD initials_height_weight VARCHAR(255) DEFAULT NULL, ADD birth_date VARCHAR(255) DEFAULT NULL, ADD primary_source_qualification VARCHAR(1000) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medical_history DROP medical_history_ctll, DROP disease, DROP comment');
        $this->addSql('ALTER TABLE susar_eu DROP receive_date, DROP receipt_date, DROP gateway_date, DROP initials_height_weight, DROP birth_date, DROP primary_source_qualification');
    }
}
