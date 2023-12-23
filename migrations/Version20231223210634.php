<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231223210634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos ADD ad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D94F34D596 FOREIGN KEY (ad_id) REFERENCES ads (id)');
        $this->addSql('CREATE INDEX IDX_876E0D94F34D596 ON photos (ad_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D94F34D596');
        $this->addSql('DROP INDEX IDX_876E0D94F34D596 ON photos');
        $this->addSql('ALTER TABLE photos DROP ad_id');
    }
}
