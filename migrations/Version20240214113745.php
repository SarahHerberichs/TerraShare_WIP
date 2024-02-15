<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214113745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E94F34D596');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E94F34D596 FOREIGN KEY (ad_id) REFERENCES ads (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E94F34D596');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E94F34D596 FOREIGN KEY (ad_id) REFERENCES ads (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
