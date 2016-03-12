<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160311200449 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE town ADD slug VARCHAR(128) NOT NULL, CHANGE name title VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4CE6C7A4989D9B62 ON town (slug)');
        $this->addSql('ALTER TABLE region ADD slug VARCHAR(128) NOT NULL, CHANGE name title VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F62F176989D9B62 ON region (slug)');
        $this->addSql('ALTER TABLE organize ADD title VARCHAR(255) NOT NULL, ADD slug VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D24AB957989D9B62 ON organize (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_D24AB957989D9B62 ON organize');
        $this->addSql('ALTER TABLE organize DROP title, DROP slug');
        $this->addSql('DROP INDEX UNIQ_F62F176989D9B62 ON region');
        $this->addSql('ALTER TABLE region DROP slug, CHANGE title name VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_4CE6C7A4989D9B62 ON town');
        $this->addSql('ALTER TABLE town DROP slug, CHANGE title name VARCHAR(255) NOT NULL');
    }
}
