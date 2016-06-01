<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160601144104 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_organize DROP FOREIGN KEY FK_32C75D57829155C2');
        $this->addSql('ALTER TABLE user_organize DROP FOREIGN KEY FK_32C75D576B3CA4B');
        $this->addSql('ALTER TABLE user_organize DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_organize ADD CONSTRAINT FK_32C75D57829155C2 FOREIGN KEY (id_organize) REFERENCES organize (id)');
        $this->addSql('ALTER TABLE user_organize ADD CONSTRAINT FK_32C75D576B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_organize ADD PRIMARY KEY (id_organize, id_user)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_organize DROP FOREIGN KEY FK_32C75D57829155C2');
        $this->addSql('ALTER TABLE user_organize DROP FOREIGN KEY FK_32C75D576B3CA4B');
        $this->addSql('ALTER TABLE user_organize DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_organize ADD CONSTRAINT FK_32C75D57829155C2 FOREIGN KEY (id_organize) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_organize ADD CONSTRAINT FK_32C75D576B3CA4B FOREIGN KEY (id_user) REFERENCES organize (id)');
        $this->addSql('ALTER TABLE user_organize ADD PRIMARY KEY (id_user, id_organize)');
    }
}
