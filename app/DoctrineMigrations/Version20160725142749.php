<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160725142749 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting DROP FOREIGN KEY FK_FC28DA55852BBECD');
        $this->addSql('DROP INDEX UNIQ_FC28DA55852BBECD ON voting');
        $this->addSql('ALTER TABLE voting DROP forum');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting ADD forum INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voting ADD CONSTRAINT FK_FC28DA55852BBECD FOREIGN KEY (forum) REFERENCES forum (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC28DA55852BBECD ON voting (forum)');
    }
}
