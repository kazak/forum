<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151216131657 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organize ADD town INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB9574CE6C7A4 FOREIGN KEY (town) REFERENCES town (id)');
        $this->addSql('CREATE INDEX IDX_D24AB9574CE6C7A4 ON organize (town)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB9574CE6C7A4');
        $this->addSql('DROP INDEX IDX_D24AB9574CE6C7A4 ON organize');
        $this->addSql('ALTER TABLE organize DROP town');
    }
}
