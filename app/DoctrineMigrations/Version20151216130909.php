<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151216130909 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE organize (id INT AUTO_INCREMENT NOT NULL, admin INT DEFAULT NULL, lat VARCHAR(255) NOT NULL, lng VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, background VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, INDEX IDX_D24AB957880E0D76 (admin), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB957880E0D76 FOREIGN KEY (admin) REFERENCES fos_user_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE organize');
    }
}
