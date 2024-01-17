<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116114205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ads ADD type_id INT DEFAULT NULL, ADD transaction_id INT DEFAULT NULL, ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F620C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F6202FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F6206BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_7EC9F620C54C8C93 ON ads (type_id)');
        $this->addSql('CREATE INDEX IDX_7EC9F6202FC0CB0F ON ads (transaction_id)');
        $this->addSql('CREATE INDEX IDX_7EC9F6206BF700BD ON ads (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ads DROP FOREIGN KEY FK_7EC9F6206BF700BD');
        $this->addSql('ALTER TABLE ads DROP FOREIGN KEY FK_7EC9F6202FC0CB0F');
        $this->addSql('ALTER TABLE ads DROP FOREIGN KEY FK_7EC9F620C54C8C93');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP INDEX IDX_7EC9F620C54C8C93 ON ads');
        $this->addSql('DROP INDEX IDX_7EC9F6202FC0CB0F ON ads');
        $this->addSql('DROP INDEX IDX_7EC9F6206BF700BD ON ads');
        $this->addSql('ALTER TABLE ads DROP type_id, DROP transaction_id, DROP status_id');
    }
}
