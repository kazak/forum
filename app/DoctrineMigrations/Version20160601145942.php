<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160601145942 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB957880E0D76');
        $this->addSql('DROP INDEX IDX_D24AB957880E0D76 ON organize');
        $this->addSql('ALTER TABLE organize CHANGE admin admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB957642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D24AB957642B8210 ON organize (admin_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organize DROP FOREIGN KEY FK_D24AB957642B8210');
        $this->addSql('DROP INDEX IDX_D24AB957642B8210 ON organize');
        $this->addSql('ALTER TABLE organize CHANGE admin_id admin INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organize ADD CONSTRAINT FK_D24AB957880E0D76 FOREIGN KEY (admin) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D24AB957880E0D76 ON organize (admin)');
    }
}
