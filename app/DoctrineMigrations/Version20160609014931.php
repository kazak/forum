<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160609014931 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_post ADD forum INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5A852BBECD FOREIGN KEY (forum) REFERENCES forum (id)');
        $this->addSql('CREATE INDEX IDX_996BCC5A852BBECD ON forum_post (forum)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5A852BBECD');
        $this->addSql('DROP INDEX IDX_996BCC5A852BBECD ON forum_post');
        $this->addSql('ALTER TABLE forum_post DROP forum');
    }
}
