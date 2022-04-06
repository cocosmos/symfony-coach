<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406122416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA380838C8A');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA32F68B530');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3881ECFA7');
        $this->addSql('DROP INDEX IDX_97A0ADA3881ECFA7 ON ticket');
        $this->addSql('DROP INDEX IDX_97A0ADA380838C8A ON ticket');
        $this->addSql('DROP INDEX IDX_97A0ADA32F68B530 ON ticket');
        $this->addSql('ALTER TABLE ticket ADD group_id INT NOT NULL, ADD priority_id INT NOT NULL, DROP group_id_id, DROP priority_id_id, CHANGE status_id_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA36BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3FE54D947 ON ticket (group_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA36BF700BD ON ticket (status_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3497B19F9 ON ticket (priority_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3FE54D947');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA36BF700BD');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3497B19F9');
        $this->addSql('DROP INDEX IDX_97A0ADA3FE54D947 ON ticket');
        $this->addSql('DROP INDEX IDX_97A0ADA36BF700BD ON ticket');
        $this->addSql('DROP INDEX IDX_97A0ADA3497B19F9 ON ticket');
        $this->addSql('ALTER TABLE ticket ADD group_id_id INT NOT NULL, ADD priority_id_id INT NOT NULL, DROP group_id, DROP priority_id, CHANGE status_id status_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA380838C8A FOREIGN KEY (priority_id_id) REFERENCES priority (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA32F68B530 FOREIGN KEY (group_id_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3881ECFA7 FOREIGN KEY (status_id_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3881ECFA7 ON ticket (status_id_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA380838C8A ON ticket (priority_id_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA32F68B530 ON ticket (group_id_id)');
    }
}
