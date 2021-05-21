<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521085245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, titre VARCHAR(30) NOT NULL, description LONGTEXT NOT NULL, auteur VARCHAR(30) NOT NULL, photo VARCHAR(50) NOT NULL, INDEX IDX_23A0E666A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, ville VARCHAR(30) NOT NULL, cp INT NOT NULL, adresse VARCHAR(30) NOT NULL, date DATE NOT NULL, titre VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement_membre (evenement_id INT NOT NULL, membre_id INT NOT NULL, INDEX IDX_45412BABFD02F13 (evenement_id), INDEX IDX_45412BAB6A99F74A (membre_id), PRIMARY KEY(evenement_id, membre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(20) NOT NULL, prenom VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, ville VARCHAR(20) NOT NULL, cp INT NOT NULL, adresse LONGTEXT NOT NULL, photo VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_F6B4FB2986CC499D (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oeuvre (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, reference INT NOT NULL, categorie VARCHAR(70) NOT NULL, nom_oeuvre VARCHAR(50) NOT NULL, année DATE NOT NULL, dimension VARCHAR(30) NOT NULL, prix INT NOT NULL, photo VARCHAR(255) NOT NULL, stock INT NOT NULL, INDEX IDX_35FE2EFE6A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E666A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE evenement_membre ADD CONSTRAINT FK_45412BABFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_membre ADD CONSTRAINT FK_45412BAB6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oeuvre ADD CONSTRAINT FK_35FE2EFE6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement_membre DROP FOREIGN KEY FK_45412BABFD02F13');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E666A99F74A');
        $this->addSql('ALTER TABLE evenement_membre DROP FOREIGN KEY FK_45412BAB6A99F74A');
        $this->addSql('ALTER TABLE oeuvre DROP FOREIGN KEY FK_35FE2EFE6A99F74A');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE evenement_membre');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE oeuvre');
    }
}
