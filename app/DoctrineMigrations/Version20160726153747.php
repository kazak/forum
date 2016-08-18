<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160726153747 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB9572D5B0234');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB9572D5B0234 FOREIGN KEY (city) REFERENCES city (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB9572D5B0234');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB9572D5B0234 FOREIGN KEY (city) REFERENCES city (id)');
    }
}
