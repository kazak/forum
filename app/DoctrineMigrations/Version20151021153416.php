<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151021153416 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE seo (id INT AUTO_INCREMENT NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Town (id INT AUTO_INCREMENT NOT NULL, seo_id INT DEFAULT NULL, coordinate_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, background VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, UNIQUE INDEX UNIQ_ECD4689A97E3DD86 (seo_id), UNIQUE INDEX UNIQ_ECD4689A98BBE953 (coordinate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coordinate (id INT AUTO_INCREMENT NOT NULL, polygon LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oblast (id INT AUTO_INCREMENT NOT NULL, coordinate_id INT DEFAULT NULL, seo_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, background VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, UNIQUE INDEX UNIQ_C00D4EBD98BBE953 (coordinate_id), UNIQUE INDEX UNIQ_C00D4EBD97E3DD86 (seo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_page (id INT AUTO_INCREMENT NOT NULL, seo_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, body LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_F4DA3AB0989D9B62 (slug), UNIQUE INDEX UNIQ_F4DA3AB097E3DD86 (seo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Town ADD CONSTRAINT FK_ECD4689A97E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE Town ADD CONSTRAINT FK_ECD4689A98BBE953 FOREIGN KEY (coordinate_id) REFERENCES coordinate (id)');
        $this->addSql('ALTER TABLE oblast ADD CONSTRAINT FK_C00D4EBD98BBE953 FOREIGN KEY (coordinate_id) REFERENCES coordinate (id)');
        $this->addSql('ALTER TABLE oblast ADD CONSTRAINT FK_C00D4EBD97E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE blog_page ADD CONSTRAINT FK_F4DA3AB097E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Town DROP FOREIGN KEY FK_ECD4689A97E3DD86');
        $this->addSql('ALTER TABLE oblast DROP FOREIGN KEY FK_C00D4EBD97E3DD86');
        $this->addSql('ALTER TABLE blog_page DROP FOREIGN KEY FK_F4DA3AB097E3DD86');
        $this->addSql('ALTER TABLE Town DROP FOREIGN KEY FK_ECD4689A98BBE953');
        $this->addSql('ALTER TABLE oblast DROP FOREIGN KEY FK_C00D4EBD98BBE953');
        $this->addSql('DROP TABLE seo');
        $this->addSql('DROP TABLE Town');
        $this->addSql('DROP TABLE coordinate');
        $this->addSql('DROP TABLE oblast');
        $this->addSql('DROP TABLE blog_page');
    }
}
