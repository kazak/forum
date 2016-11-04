<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161104170106 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, owner INT DEFAULT NULL, organize INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_880E0D76CF60E67C (owner), INDEX IDX_880E0D76D24AB957 (organize), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76CF60E67C FOREIGN KEY (owner) REFERENCES user (id)');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76D24AB957 FOREIGN KEY (organize) REFERENCES organize (id)');
        $this->addSql('DROP TABLE admin_organize');
        $this->addSql('ALTER TABLE user DROP status_name');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_organize (id_organize INT NOT NULL, id_admin INT NOT NULL, INDEX IDX_46EABE51829155C2 (id_organize), INDEX IDX_46EABE51668B4C46 (id_admin), PRIMARY KEY(id_organize, id_admin)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_organize ADD CONSTRAINT FK_46EABE51668B4C46 FOREIGN KEY (id_admin) REFERENCES user (id)');
        $this->addSql('ALTER TABLE admin_organize ADD CONSTRAINT FK_46EABE51829155C2 FOREIGN KEY (id_organize) REFERENCES organize (id)');
        $this->addSql('DROP TABLE admin');
        $this->addSql('ALTER TABLE user ADD status_name VARCHAR(30) DEFAULT \'Житель\'');
    }
}
