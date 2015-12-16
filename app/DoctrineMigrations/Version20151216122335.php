<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151216122335 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE town ADD region INT DEFAULT NULL');
        $this->addSql('ALTER TABLE town ADD CONSTRAINT FK_4CE6C7A4F62F176 FOREIGN KEY (region) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_4CE6C7A4F62F176 ON town (region)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE town DROP FOREIGN KEY FK_4CE6C7A4F62F176');
        $this->addSql('DROP INDEX IDX_4CE6C7A4F62F176 ON town');
        $this->addSql('ALTER TABLE town DROP region');
    }
}
