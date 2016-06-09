<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160608234852 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, date_of_birth DATETIME DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, lastname VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, biography VARCHAR(1000) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, locale VARCHAR(8) DEFAULT NULL, timezone VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, facebook_uid VARCHAR(255) DEFAULT NULL, facebook_name VARCHAR(255) DEFAULT NULL, facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', twitter_uid VARCHAR(255) DEFAULT NULL, twitter_name VARCHAR(255) DEFAULT NULL, twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', gplus_uid VARCHAR(255) DEFAULT NULL, gplus_name VARCHAR(255) DEFAULT NULL, gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', token VARCHAR(255) DEFAULT NULL, two_step_code VARCHAR(255) DEFAULT NULL, status_name VARCHAR(30) DEFAULT \'Житель\', UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE my_custom_user_group_association_table_name (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_D1C9A4DCA76ED395 (user_id), INDEX IDX_D1C9A4DCFE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8F02BF9D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, region INT DEFAULT NULL, visible TINYINT(1) DEFAULT NULL, slug VARCHAR(128) DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, originalImage VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, lng VARCHAR(255) DEFAULT NULL, lat VARCHAR(255) DEFAULT NULL, gallery LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_2D5B0234F62F176 (region), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, visible TINYINT(1) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, balance VARCHAR(255) DEFAULT NULL, vip TINYINT(1) DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, originalImage VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voting_params (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_voting_param (id_voting_param INT NOT NULL, id_user INT NOT NULL, INDEX IDX_E8501FCE4B5D16BE (id_voting_param), INDEX IDX_E8501FCE6B3CA4B (id_user), PRIMARY KEY(id_voting_param, id_user)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, icon VARCHAR(255) DEFAULT NULL, background LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', slug VARCHAR(128) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, originalImage VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, lng VARCHAR(255) DEFAULT NULL, lat VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F62F176989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, city_id INT DEFAULT NULL, startPage TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, originalImage VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_1DD3995098260155 (region_id), INDEX IDX_1DD399508BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organize (id INT AUTO_INCREMENT NOT NULL, city INT DEFAULT NULL, visible TINYINT(1) DEFAULT NULL, slug VARCHAR(128) NOT NULL, info LONGTEXT DEFAULT NULL, message LONGTEXT DEFAULT NULL, address LONGTEXT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, originalImage VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, lng VARCHAR(255) DEFAULT NULL, lat VARCHAR(255) DEFAULT NULL, gallery LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_D24AB957989D9B62 (slug), INDEX IDX_D24AB9572D5B0234 (city), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_organize (id_organize INT NOT NULL, id_admin INT NOT NULL, INDEX IDX_46EABE51829155C2 (id_organize), INDEX IDX_46EABE51668B4C46 (id_admin), PRIMARY KEY(id_organize, id_admin)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_organize (id_organize INT NOT NULL, id_user INT NOT NULL, INDEX IDX_32C75D57829155C2 (id_organize), INDEX IDX_32C75D576B3CA4B (id_user), PRIMARY KEY(id_organize, id_user)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, organize INT DEFAULT NULL, city INT DEFAULT NULL, region INT DEFAULT NULL, voting_id INT DEFAULT NULL, visible TINYINT(1) DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_852BBECDD24AB957 (organize), INDEX IDX_852BBECD2D5B0234 (city), INDEX IDX_852BBECDF62F176 (region), UNIQUE INDEX UNIQ_852BBECD4254ACF8 (voting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rules (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post (id INT AUTO_INCREMENT NOT NULL, owner INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, visible TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_996BCC5ACF60E67C (owner), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voting (id INT AUTO_INCREMENT NOT NULL, forum INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_FC28DA55852BBECD (forum), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE my_custom_user_group_association_table_name ADD CONSTRAINT FK_D1C9A4DCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE my_custom_user_group_association_table_name ADD CONSTRAINT FK_D1C9A4DCFE54D947 FOREIGN KEY (group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F62F176 FOREIGN KEY (region) REFERENCES region (id)');
        $this->addSql('ALTER TABLE user_voting_param ADD CONSTRAINT FK_E8501FCE4B5D16BE FOREIGN KEY (id_voting_param) REFERENCES voting_params (id)');
        $this->addSql('ALTER TABLE user_voting_param ADD CONSTRAINT FK_E8501FCE6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD3995098260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399508BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB9572D5B0234 FOREIGN KEY (city) REFERENCES city (id)');
        $this->addSql('ALTER TABLE admin_organize ADD CONSTRAINT FK_46EABE51829155C2 FOREIGN KEY (id_organize) REFERENCES organize (id)');
        $this->addSql('ALTER TABLE admin_organize ADD CONSTRAINT FK_46EABE51668B4C46 FOREIGN KEY (id_admin) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_organize ADD CONSTRAINT FK_32C75D57829155C2 FOREIGN KEY (id_organize) REFERENCES organize (id)');
        $this->addSql('ALTER TABLE user_organize ADD CONSTRAINT FK_32C75D576B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDD24AB957 FOREIGN KEY (organize) REFERENCES organize (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD2D5B0234 FOREIGN KEY (city) REFERENCES city (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDF62F176 FOREIGN KEY (region) REFERENCES region (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD4254ACF8 FOREIGN KEY (voting_id) REFERENCES voting (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5ACF60E67C FOREIGN KEY (owner) REFERENCES user (id)');
        $this->addSql('ALTER TABLE voting ADD CONSTRAINT FK_FC28DA55852BBECD FOREIGN KEY (forum) REFERENCES forum (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE my_custom_user_group_association_table_name DROP FOREIGN KEY FK_D1C9A4DCA76ED395');
        $this->addSql('ALTER TABLE user_voting_param DROP FOREIGN KEY FK_E8501FCE6B3CA4B');
        $this->addSql('ALTER TABLE admin_organize DROP FOREIGN KEY FK_46EABE51668B4C46');
        $this->addSql('ALTER TABLE user_organize DROP FOREIGN KEY FK_32C75D576B3CA4B');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5ACF60E67C');
        $this->addSql('ALTER TABLE my_custom_user_group_association_table_name DROP FOREIGN KEY FK_D1C9A4DCFE54D947');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399508BAC62AF');
        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB9572D5B0234');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD2D5B0234');
        $this->addSql('ALTER TABLE user_voting_param DROP FOREIGN KEY FK_E8501FCE4B5D16BE');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234F62F176');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD3995098260155');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDF62F176');
        $this->addSql('ALTER TABLE admin_organize DROP FOREIGN KEY FK_46EABE51829155C2');
        $this->addSql('ALTER TABLE user_organize DROP FOREIGN KEY FK_32C75D57829155C2');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDD24AB957');
        $this->addSql('ALTER TABLE voting DROP FOREIGN KEY FK_FC28DA55852BBECD');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD4254ACF8');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE my_custom_user_group_association_table_name');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE voting_params');
        $this->addSql('DROP TABLE user_voting_param');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE organize');
        $this->addSql('DROP TABLE admin_organize');
        $this->addSql('DROP TABLE user_organize');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE rules');
        $this->addSql('DROP TABLE forum_post');
        $this->addSql('DROP TABLE voting');
    }
}
