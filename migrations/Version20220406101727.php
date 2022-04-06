<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406101727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE admin_link_token admin_link_token VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE `group` ADD event_id INT NOT NULL, CHANGE last_archived last_archived DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C571F7E88B FOREIGN KEY (event_id) REFERENCES `event` (id)');
        $this->addSql('CREATE INDEX IDX_6DC044C571F7E88B ON `group` (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `event` CHANGE admin_link_token admin_link_token VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C571F7E88B');
        $this->addSql('DROP INDEX IDX_6DC044C571F7E88B ON `group`');
        $this->addSql('ALTER TABLE `group` DROP event_id, CHANGE last_archived last_archived DATE DEFAULT NULL');
    }
}
