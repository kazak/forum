<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160330200144 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city CHANGE lng lng VARCHAR(255) DEFAULT NULL, CHANGE lat lat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE region CHANGE lng lng VARCHAR(255) DEFAULT NULL, CHANGE lat lat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE organize CHANGE lat lat VARCHAR(255) DEFAULT NULL, CHANGE lng lng VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city CHANGE lng lng VARCHAR(255) NOT NULL, CHANGE lat lat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organize CHANGE lng lng VARCHAR(255) NOT NULL, CHANGE lat lat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE region CHANGE lng lng VARCHAR(255) NOT NULL, CHANGE lat lat VARCHAR(255) NOT NULL');
    }
}
