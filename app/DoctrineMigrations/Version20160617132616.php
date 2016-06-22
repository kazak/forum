<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160617132616 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting_params ADD voting INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voting_params ADD CONSTRAINT FK_89B4DDF3FC28DA55 FOREIGN KEY (voting) REFERENCES voting (id)');
        $this->addSql('CREATE INDEX IDX_89B4DDF3FC28DA55 ON voting_params (voting)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting_params DROP FOREIGN KEY FK_89B4DDF3FC28DA55');
        $this->addSql('DROP INDEX IDX_89B4DDF3FC28DA55 ON voting_params');
        $this->addSql('ALTER TABLE voting_params DROP voting');
    }
}
