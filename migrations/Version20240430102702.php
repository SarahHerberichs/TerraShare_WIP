<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430102702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_transaction (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, transaction_id INT DEFAULT NULL, INDEX IDX_392ED240C54C8C93 (type_id), INDEX IDX_392ED2402FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE type_transaction ADD CONSTRAINT FK_392ED240C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE type_transaction ADD CONSTRAINT FK_392ED2402FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_transaction DROP FOREIGN KEY FK_392ED240C54C8C93');
        $this->addSql('ALTER TABLE type_transaction DROP FOREIGN KEY FK_392ED2402FC0CB0F');
        $this->addSql('DROP TABLE type_transaction');
    }
}
