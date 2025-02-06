<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206140418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sceance DROP FOREIGN KEY FK_2D854BFE62BB7AEE');
        $this->addSql('DROP INDEX IDX_2D854BFE62BB7AEE ON sceance');
        $this->addSql('ALTER TABLE sceance DROP programme_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sceance ADD programme_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sceance ADD CONSTRAINT FK_2D854BFE62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
        $this->addSql('CREATE INDEX IDX_2D854BFE62BB7AEE ON sceance (programme_id)');
    }
}
