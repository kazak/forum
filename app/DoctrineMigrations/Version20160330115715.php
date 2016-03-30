<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160330115715 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city CHANGE image image LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE blog_post ADD description LONGTEXT DEFAULT NULL, DROP body');
        $this->addSql('ALTER TABLE region CHANGE image image LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE forum_team CHANGE name title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organize ADD image LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', DROP name');
        $this->addSql('ALTER TABLE forum CHANGE name title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rules ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE forum_post ADD title VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_post ADD body LONGTEXT NOT NULL, DROP description');
        $this->addSql('ALTER TABLE city CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE forum CHANGE title name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE forum_post DROP title');
        $this->addSql('ALTER TABLE forum_team CHANGE title name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organize ADD name VARCHAR(255) NOT NULL, DROP image');
        $this->addSql('ALTER TABLE region CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE rules DROP title');
    }
}
