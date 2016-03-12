<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160311203302 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB9574CE6C7A4');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, region INT DEFAULT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, background VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, lng VARCHAR(255) NOT NULL, lat VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_2D5B0234989D9B62 (slug), INDEX IDX_2D5B0234F62F176 (region), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F62F176 FOREIGN KEY (region) REFERENCES region (id)');
        $this->addSql('DROP TABLE town');
        $this->addSql('DROP INDEX IDX_D24AB9574CE6C7A4 ON organize');
        $this->addSql('ALTER TABLE organize CHANGE town city INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB9572D5B0234 FOREIGN KEY (city) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_D24AB9572D5B0234 ON organize (city)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB9572D5B0234');
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, region INT DEFAULT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, background VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, lng VARCHAR(255) NOT NULL, lat VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_4CE6C7A4989D9B62 (slug), INDEX IDX_4CE6C7A4F62F176 (region), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE town ADD CONSTRAINT FK_4CE6C7A4F62F176 FOREIGN KEY (region) REFERENCES region (id)');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP INDEX IDX_D24AB9572D5B0234 ON organize');
        $this->addSql('ALTER TABLE organize CHANGE city town INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB9574CE6C7A4 FOREIGN KEY (town) REFERENCES town (id)');
        $this->addSql('CREATE INDEX IDX_D24AB9574CE6C7A4 ON organize (town)');
    }
}
