<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211111507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE repetition_serie (id INT AUTO_INCREMENT NOT NULL, sceance_id INT DEFAULT NULL, repetition INT NOT NULL, serie INT NOT NULL, INDEX IDX_7C42A7C057447AA6 (sceance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE repetition_serie ADD CONSTRAINT FK_7C42A7C057447AA6 FOREIGN KEY (sceance_id) REFERENCES sceance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repetition_serie DROP FOREIGN KEY FK_7C42A7C057447AA6');
        $this->addSql('DROP TABLE repetition_serie');
    }
}
