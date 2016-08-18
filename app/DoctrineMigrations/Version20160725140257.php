<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160725140257 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD4254ACF8');
        $this->addSql('DROP INDEX UNIQ_852BBECD4254ACF8 ON forum');
        $this->addSql('ALTER TABLE forum CHANGE voting_id voting INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDFC28DA55 FOREIGN KEY (voting) REFERENCES voting (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_852BBECDFC28DA55 ON forum (voting)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDFC28DA55');
        $this->addSql('DROP INDEX UNIQ_852BBECDFC28DA55 ON forum');
        $this->addSql('ALTER TABLE forum CHANGE voting voting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD4254ACF8 FOREIGN KEY (voting_id) REFERENCES voting (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_852BBECD4254ACF8 ON forum (voting_id)');
    }
}
