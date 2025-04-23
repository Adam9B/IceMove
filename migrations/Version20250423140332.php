<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423140332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice ADD body_part VARCHAR(255) NOT NULL, ADD equipment VARCHAR(255) NOT NULL, ADD gif_url VARCHAR(255) NOT NULL, ADD id_exo VARCHAR(255) NOT NULL, ADD target VARCHAR(255) NOT NULL, ADD secondary_muscles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD instructions LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP description');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice ADD description LONGTEXT DEFAULT NULL, DROP body_part, DROP equipment, DROP gif_url, DROP id_exo, DROP target, DROP secondary_muscles, DROP instructions');
    }
}
