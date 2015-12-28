<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151226232937 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE organize (id INT AUTO_INCREMENT NOT NULL, admin INT DEFAULT NULL, town INT DEFAULT NULL, lat VARCHAR(255) NOT NULL, lng VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, background VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, INDEX IDX_D24AB957880E0D76 (admin), INDEX IDX_D24AB9574CE6C7A4 (town), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, organize INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, INDEX IDX_852BBECDD24AB957 (organize), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, background LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', lng VARCHAR(255) NOT NULL, lat VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, INDEX IDX_BA5AE01D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, region INT DEFAULT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, background VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, lng VARCHAR(255) NOT NULL, lat VARCHAR(255) NOT NULL, INDEX IDX_4CE6C7A4F62F176 (region), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_team (id INT AUTO_INCREMENT NOT NULL, forum INT DEFAULT NULL, admin INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, `update` DATETIME NOT NULL, `create` DATETIME NOT NULL, INDEX IDX_70106C8852BBECD (forum), INDEX IDX_70106C8880E0D76 (admin), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post (id INT AUTO_INCREMENT NOT NULL, team INT DEFAULT NULL, owner INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, `create` DATETIME NOT NULL, INDEX IDX_996BCC5AC4E0A61F (team), INDEX IDX_996BCC5ACF60E67C (owner), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, date_of_birth DATETIME DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, lastname VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, biography VARCHAR(1000) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, locale VARCHAR(8) DEFAULT NULL, timezone VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, facebook_uid VARCHAR(255) DEFAULT NULL, facebook_name VARCHAR(255) DEFAULT NULL, facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', twitter_uid VARCHAR(255) DEFAULT NULL, twitter_name VARCHAR(255) DEFAULT NULL, twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', gplus_uid VARCHAR(255) DEFAULT NULL, gplus_name VARCHAR(255) DEFAULT NULL, gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', token VARCHAR(255) DEFAULT NULL, two_step_code VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C560D76192FC23A8 (username_canonical), UNIQUE INDEX UNIQ_C560D761A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user_user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_B3C77447A76ED395 (user_id), INDEX IDX_B3C77447FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_583D1F3E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB957880E0D76 FOREIGN KEY (admin) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB9574CE6C7A4 FOREIGN KEY (town) REFERENCES town (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDD24AB957 FOREIGN KEY (organize) REFERENCES organize (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE town ADD CONSTRAINT FK_4CE6C7A4F62F176 FOREIGN KEY (region) REFERENCES region (id)');
        $this->addSql('ALTER TABLE forum_team ADD CONSTRAINT FK_70106C8852BBECD FOREIGN KEY (forum) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE forum_team ADD CONSTRAINT FK_70106C8880E0D76 FOREIGN KEY (admin) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AC4E0A61F FOREIGN KEY (team) REFERENCES forum_team (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5ACF60E67C FOREIGN KEY (owner) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES fos_user_group (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDD24AB957');
        $this->addSql('ALTER TABLE forum_team DROP FOREIGN KEY FK_70106C8852BBECD');
        $this->addSql('ALTER TABLE town DROP FOREIGN KEY FK_4CE6C7A4F62F176');
        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB9574CE6C7A4');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5AC4E0A61F');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB957880E0D76');
        $this->addSql('ALTER TABLE forum_team DROP FOREIGN KEY FK_70106C8880E0D76');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5ACF60E67C');
        $this->addSql('ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447A76ED395');
        $this->addSql('ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447FE54D947');
        $this->addSql('DROP TABLE organize');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE town');
        $this->addSql('DROP TABLE forum_team');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE forum_post');
        $this->addSql('DROP TABLE fos_user_user');
        $this->addSql('DROP TABLE fos_user_user_group');
        $this->addSql('DROP TABLE fos_user_group');
    }
}
