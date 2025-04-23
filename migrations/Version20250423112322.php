<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423112322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, date DATETIME NOT NULL, UNIQUE INDEX UNIQ_23A0E66FB88E14F (utilisateur_id), INDEX IDX_23A0E66BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evolution (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, date DATETIME NOT NULL, poid INT NOT NULL, répétition INT NOT NULL, UNIQUE INDEX UNIQ_420C2893FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, titre VARCHAR(255) NOT NULL, date DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_3DDCB9FFFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repetition_serie (id INT AUTO_INCREMENT NOT NULL, sceance_id INT DEFAULT NULL, repetition INT NOT NULL, serie INT NOT NULL, INDEX IDX_7C42A7C057447AA6 (sceance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sceance (id INT AUTO_INCREMENT NOT NULL, programme_id INT DEFAULT NULL, utilisateur_id INT NOT NULL, titre VARCHAR(255) NOT NULL, jour VARCHAR(20) NOT NULL, date DATE NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_2D854BFE62BB7AEE (programme_id), INDEX IDX_2D854BFEFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sceance_exercice (sceance_id INT NOT NULL, exercice_id INT NOT NULL, INDEX IDX_8107972357447AA6 (sceance_id), INDEX IDX_8107972389D40298 (exercice_id), PRIMARY KEY(sceance_id, exercice_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE evolution ADD CONSTRAINT FK_420C2893FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FFFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE repetition_serie ADD CONSTRAINT FK_7C42A7C057447AA6 FOREIGN KEY (sceance_id) REFERENCES sceance (id)');
        $this->addSql('ALTER TABLE sceance ADD CONSTRAINT FK_2D854BFE62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id)');
        $this->addSql('ALTER TABLE sceance ADD CONSTRAINT FK_2D854BFEFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE sceance_exercice ADD CONSTRAINT FK_8107972357447AA6 FOREIGN KEY (sceance_id) REFERENCES sceance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sceance_exercice ADD CONSTRAINT FK_8107972389D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66FB88E14F');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BCF5E72D');
        $this->addSql('ALTER TABLE evolution DROP FOREIGN KEY FK_420C2893FB88E14F');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FFFB88E14F');
        $this->addSql('ALTER TABLE repetition_serie DROP FOREIGN KEY FK_7C42A7C057447AA6');
        $this->addSql('ALTER TABLE sceance DROP FOREIGN KEY FK_2D854BFE62BB7AEE');
        $this->addSql('ALTER TABLE sceance DROP FOREIGN KEY FK_2D854BFEFB88E14F');
        $this->addSql('ALTER TABLE sceance_exercice DROP FOREIGN KEY FK_8107972357447AA6');
        $this->addSql('ALTER TABLE sceance_exercice DROP FOREIGN KEY FK_8107972389D40298');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE evolution');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE repetition_serie');
        $this->addSql('DROP TABLE sceance');
        $this->addSql('DROP TABLE sceance_exercice');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
