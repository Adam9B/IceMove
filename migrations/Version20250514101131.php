<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514101131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE programme_sceance (id INT AUTO_INCREMENT NOT NULL, programme_id INT NOT NULL, sceance_id INT NOT NULL, jour VARCHAR(50) NOT NULL, INDEX IDX_F626067562BB7AEE (programme_id), INDEX IDX_F626067557447AA6 (sceance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE programme_sceance ADD CONSTRAINT FK_F626067562BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE programme_sceance ADD CONSTRAINT FK_F626067557447AA6 FOREIGN KEY (sceance_id) REFERENCES sceance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE programme_sceance DROP FOREIGN KEY FK_F626067562BB7AEE');
        $this->addSql('ALTER TABLE programme_sceance DROP FOREIGN KEY FK_F626067557447AA6');
        $this->addSql('DROP TABLE programme_sceance');
    }
}
