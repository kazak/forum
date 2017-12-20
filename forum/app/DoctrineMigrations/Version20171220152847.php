<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171220152847 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE my_custom_user_group_association_table_name (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_D1C9A4DCA76ED395 (user_id), INDEX IDX_D1C9A4DCFE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, city_id INT DEFAULT NULL, startPage TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_1DD3995098260155 (region_id), INDEX IDX_1DD399508BAC62AF (city_id), INDEX created (created), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(128) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, visible TINYINT(1) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, balance VARCHAR(255) DEFAULT NULL, vip TINYINT(1) DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX slug (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mail (id INT AUTO_INCREMENT NOT NULL, owner INT DEFAULT NULL, sender INT DEFAULT NULL, new TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_5126AC48CF60E67C (owner), INDEX IDX_5126AC485F004ACF (sender), INDEX created (created), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE my_custom_user_group_association_table_name ADD CONSTRAINT FK_D1C9A4DCA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE my_custom_user_group_association_table_name ADD CONSTRAINT FK_D1C9A4DCFE54D947 FOREIGN KEY (group_id) REFERENCES fos_user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD3995098260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399508BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mail ADD CONSTRAINT FK_5126AC48CF60E67C FOREIGN KEY (owner) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE mail ADD CONSTRAINT FK_5126AC485F004ACF FOREIGN KEY (sender) REFERENCES fos_user_user (id)');
        $this->addSql('DROP TABLE fos_user_user_group');
        $this->addSql('ALTER TABLE forum_voting ADD visible TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user_user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_B3C77447A76ED395 (user_id), INDEX IDX_B3C77447FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES fos_user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE my_custom_user_group_association_table_name');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE mail');
        $this->addSql('ALTER TABLE forum_voting DROP visible');
    }
}
