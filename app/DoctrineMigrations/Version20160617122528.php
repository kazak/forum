<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160617122528 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partner ADD slug VARCHAR(128) DEFAULT NULL');
        $this->addSql('CREATE INDEX slug ON partner (slug)');
        $this->addSql('CREATE INDEX created ON news (created)');
        $this->addSql('CREATE INDEX created ON forum_post (created)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX created ON forum_post');
        $this->addSql('DROP INDEX created ON news');
        $this->addSql('DROP INDEX slug ON partner');
        $this->addSql('ALTER TABLE partner DROP slug');
    }
}
