<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160601155949 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_organize (id_organize INT NOT NULL, id_admin INT NOT NULL, INDEX IDX_46EABE51829155C2 (id_organize), INDEX IDX_46EABE51668B4C46 (id_admin), PRIMARY KEY(id_organize, id_admin)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_organize ADD CONSTRAINT FK_46EABE51829155C2 FOREIGN KEY (id_organize) REFERENCES organize (id)');
        $this->addSql('ALTER TABLE admin_organize ADD CONSTRAINT FK_46EABE51668B4C46 FOREIGN KEY (id_admin) REFERENCES user (id)');
        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB957642B8210');
        $this->addSql('DROP INDEX IDX_D24AB957642B8210 ON organize');
        $this->addSql('ALTER TABLE organize DROP admin_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE admin_organize');
        $this->addSql('ALTER TABLE organize ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB957642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D24AB957642B8210 ON organize (admin_id)');
    }
}
