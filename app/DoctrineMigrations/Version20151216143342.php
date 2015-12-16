<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151216143342 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, organize INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, INDEX IDX_852BBECDD24AB957 (organize), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_team (id INT AUTO_INCREMENT NOT NULL, forum INT DEFAULT NULL, admin INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, `update` DATETIME NOT NULL, `create` DATETIME NOT NULL, INDEX IDX_70106C8852BBECD (forum), INDEX IDX_70106C8880E0D76 (admin), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post (id INT AUTO_INCREMENT NOT NULL, team INT DEFAULT NULL, owner INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, `create` DATETIME NOT NULL, INDEX IDX_996BCC5AC4E0A61F (team), INDEX IDX_996BCC5ACF60E67C (owner), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDD24AB957 FOREIGN KEY (organize) REFERENCES organize (id)');
        $this->addSql('ALTER TABLE forum_team ADD CONSTRAINT FK_70106C8852BBECD FOREIGN KEY (forum) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE forum_team ADD CONSTRAINT FK_70106C8880E0D76 FOREIGN KEY (admin) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AC4E0A61F FOREIGN KEY (team) REFERENCES forum_team (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5ACF60E67C FOREIGN KEY (owner) REFERENCES fos_user_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_team DROP FOREIGN KEY FK_70106C8852BBECD');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5AC4E0A61F');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE forum_team');
        $this->addSql('DROP TABLE forum_post');
    }
}
