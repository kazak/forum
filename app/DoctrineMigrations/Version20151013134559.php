<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151013134559 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, data LONGBLOB NOT NULL, code VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E545A0C577153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seo (id INT AUTO_INCREMENT NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT NOT NULL, seo_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, active SMALLINT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, visible SMALLINT DEFAULT NULL, time_takeaway SMALLINT DEFAULT \'20\' NOT NULL, time_delivery SMALLINT DEFAULT \'60\' NOT NULL, warranty_voided SMALLINT DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_EB95123F989D9B62 (slug), UNIQUE INDEX UNIQ_EB95123F97E3DD86 (seo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, seo_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, status SMALLINT NOT NULL, priority SMALLINT NOT NULL, hide_for_search_engines TINYINT(1) NOT NULL, siteMap TINYINT(1) DEFAULT \'1\' NOT NULL, UNIQUE INDEX UNIQ_7D053A93989D9B62 (slug), UNIQUE INDEX UNIQ_7D053A9397E3DD86 (seo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postcode (postcode VARCHAR(50) NOT NULL, city VARCHAR(50) DEFAULT NULL, municiplaity VARCHAR(50) DEFAULT NULL, longitude NUMERIC(18, 12) DEFAULT NULL, latitude NUMERIC(18, 12) DEFAULT NULL, PRIMARY KEY(postcode)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE front_page_blocks (id INT AUTO_INCREMENT NOT NULL, front_page_id INT DEFAULT NULL, main_image_id INT DEFAULT NULL, secondary_image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, alternative_text VARCHAR(255) NOT NULL, priority SMALLINT NOT NULL, style VARCHAR(16) NOT NULL, url LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_EA16DAF3989D9B62 (slug), INDEX IDX_EA16DAF3D572C57E (front_page_id), INDEX IDX_EA16DAF3E4873418 (main_image_id), INDEX IDX_EA16DAF3A604F92B (secondary_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_page (id INT AUTO_INCREMENT NOT NULL, seo_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, siteMap TINYINT(1) DEFAULT \'1\' NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, body LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_D9685BE5989D9B62 (slug), UNIQUE INDEX UNIQ_D9685BE597E3DD86 (seo_id), INDEX IDX_D9685BE5DE12AB56 (created_by), INDEX IDX_D9685BE516FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8F02BF9D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_opening_hours (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, service VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, day_of_week SMALLINT DEFAULT NULL, opening_time TIME NOT NULL, closing_time TIME NOT NULL, reason LONGTEXT DEFAULT NULL, INDEX IDX_5061B319B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smshistory (id INT AUTO_INCREMENT NOT NULL, phone VARCHAR(255) NOT NULL, ip VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, date DATE NOT NULL, verified SMALLINT NOT NULL, invalid_tries_count SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_address (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, latitude NUMERIC(18, 12) DEFAULT NULL, longitude NUMERIC(18, 12) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, post_code VARCHAR(255) NOT NULL, post_office VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C70533F4B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurants_page (id INT AUTO_INCREMENT NOT NULL, seo_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, map_center_latitude NUMERIC(9, 6) DEFAULT NULL, map_center_longitude NUMERIC(9, 6) DEFAULT NULL, search_field_placeholder VARCHAR(50) NOT NULL, search_button_label VARCHAR(20) NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, body LONGTEXT DEFAULT NULL, visible SMALLINT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_735CB0A7989D9B62 (slug), UNIQUE INDEX UNIQ_735CB0A797E3DD86 (seo_id), INDEX IDX_735CB0A7DE12AB56 (created_by), INDEX IDX_735CB0A716FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_translations (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, locale VARCHAR(8) DEFAULT \'no\' NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_3B8458C7B1E7706E (restaurant_id), UNIQUE INDEX lookup_unique_idx (locale, restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE front_pages (id INT AUTO_INCREMENT NOT NULL, seo_id INT DEFAULT NULL, hero_image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, show_date DATETIME NOT NULL, hide_date DATETIME NOT NULL, status SMALLINT NOT NULL, is_default SMALLINT NOT NULL, alternative_hero_text VARCHAR(128) DEFAULT NULL, UNIQUE INDEX UNIQ_644818BC989D9B62 (slug), UNIQUE INDEX UNIQ_644818BC97E3DD86 (seo_id), INDEX IDX_644818BC98BB94C5 (hero_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE help_page (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_518083BBCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE help_menu (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F97E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9397E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE front_page_blocks ADD CONSTRAINT FK_EA16DAF3D572C57E FOREIGN KEY (front_page_id) REFERENCES front_pages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE front_page_blocks ADD CONSTRAINT FK_EA16DAF3E4873418 FOREIGN KEY (main_image_id) REFERENCES images (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE front_page_blocks ADD CONSTRAINT FK_EA16DAF3A604F92B FOREIGN KEY (secondary_image_id) REFERENCES images (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE content_page ADD CONSTRAINT FK_D9685BE597E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE content_page ADD CONSTRAINT FK_D9685BE5DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE content_page ADD CONSTRAINT FK_D9685BE516FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE restaurant_opening_hours ADD CONSTRAINT FK_5061B319B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant_address ADD CONSTRAINT FK_C70533F4B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurants_page ADD CONSTRAINT FK_735CB0A797E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE restaurants_page ADD CONSTRAINT FK_735CB0A7DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE restaurants_page ADD CONSTRAINT FK_735CB0A716FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE restaurant_translations ADD CONSTRAINT FK_3B8458C7B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE front_pages ADD CONSTRAINT FK_644818BC97E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE front_pages ADD CONSTRAINT FK_644818BC98BB94C5 FOREIGN KEY (hero_image_id) REFERENCES images (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE help_page ADD CONSTRAINT FK_518083BBCCD7E912 FOREIGN KEY (menu_id) REFERENCES help_menu (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F97E3DD86');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9397E3DD86');
        $this->addSql('ALTER TABLE content_page DROP FOREIGN KEY FK_D9685BE597E3DD86');
        $this->addSql('ALTER TABLE restaurants_page DROP FOREIGN KEY FK_735CB0A797E3DD86');
        $this->addSql('ALTER TABLE front_pages DROP FOREIGN KEY FK_644818BC97E3DD86');
        $this->addSql('ALTER TABLE front_page_blocks DROP FOREIGN KEY FK_EA16DAF3E4873418');
        $this->addSql('ALTER TABLE front_page_blocks DROP FOREIGN KEY FK_EA16DAF3A604F92B');
        $this->addSql('ALTER TABLE front_pages DROP FOREIGN KEY FK_644818BC98BB94C5');
        $this->addSql('ALTER TABLE restaurant_opening_hours DROP FOREIGN KEY FK_5061B319B1E7706E');
        $this->addSql('ALTER TABLE restaurant_address DROP FOREIGN KEY FK_C70533F4B1E7706E');
        $this->addSql('ALTER TABLE restaurant_translations DROP FOREIGN KEY FK_3B8458C7B1E7706E');
        $this->addSql('ALTER TABLE front_page_blocks DROP FOREIGN KEY FK_EA16DAF3D572C57E');
        $this->addSql('ALTER TABLE help_page DROP FOREIGN KEY FK_518083BBCCD7E912');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE seo');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE postcode');
        $this->addSql('DROP TABLE front_page_blocks');
        $this->addSql('DROP TABLE content_page');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE restaurant_opening_hours');
        $this->addSql('DROP TABLE smshistory');
        $this->addSql('DROP TABLE restaurant_address');
        $this->addSql('DROP TABLE restaurants_page');
        $this->addSql('DROP TABLE restaurant_translations');
        $this->addSql('DROP TABLE front_pages');
        $this->addSql('DROP TABLE help_page');
        $this->addSql('DROP TABLE help_menu');
    }
}
