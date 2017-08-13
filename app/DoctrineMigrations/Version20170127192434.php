<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170127192434 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_F62F176989D9B62 ON region');
        $this->addSql('ALTER TABLE region DROP icon, DROP background, DROP slug, DROP originalImage, DROP image, DROP lng, DROP lat');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE region ADD icon VARCHAR(255) DEFAULT NULL, ADD background VARCHAR(255) DEFAULT NULL, ADD slug VARCHAR(128) NOT NULL, ADD originalImage VARCHAR(255) DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD lng VARCHAR(255) DEFAULT NULL, ADD lat VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F62F176989D9B62 ON region (slug)');
    }
}
