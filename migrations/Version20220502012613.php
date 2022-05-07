<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220502012613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoriesn (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoriesn_usersn (categoriesn_id INT NOT NULL, usersn_id INT NOT NULL, INDEX IDX_39941F6B10A5065C (categoriesn_id), INDEX IDX_39941F6B1D0DCDC2 (usersn_id), PRIMARY KEY(categoriesn_id, usersn_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletters (id INT AUTO_INCREMENT NOT NULL, categoriesn_id INT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_sent TINYINT(1) NOT NULL, INDEX IDX_8ECF000C10A5065C (categoriesn_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usersn (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, is_rgpd TINYINT(1) NOT NULL, validation_token VARCHAR(255) DEFAULT NULL, is_valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categoriesn_usersn ADD CONSTRAINT FK_39941F6B10A5065C FOREIGN KEY (categoriesn_id) REFERENCES categoriesn (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categoriesn_usersn ADD CONSTRAINT FK_39941F6B1D0DCDC2 FOREIGN KEY (usersn_id) REFERENCES usersn (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletters ADD CONSTRAINT FK_8ECF000C10A5065C FOREIGN KEY (categoriesn_id) REFERENCES categoriesn (id)');
        $this->addSql('ALTER TABLE calendar CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAFFB3979 FOREIGN KEY (publications_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE publication_like ADD CONSTRAINT FK_A79BC17E38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication_like ADD CONSTRAINT FK_A79BC17EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoriesn_usersn DROP FOREIGN KEY FK_39941F6B10A5065C');
        $this->addSql('ALTER TABLE newsletters DROP FOREIGN KEY FK_8ECF000C10A5065C');
        $this->addSql('ALTER TABLE categoriesn_usersn DROP FOREIGN KEY FK_39941F6B1D0DCDC2');
        $this->addSql('DROP TABLE categoriesn');
        $this->addSql('DROP TABLE categoriesn_usersn');
        $this->addSql('DROP TABLE newsletters');
        $this->addSql('DROP TABLE usersn');
        $this->addSql('ALTER TABLE calendar CHANGE description description TEXT NOT NULL');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAFFB3979');
        $this->addSql('ALTER TABLE publication CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE publication_like DROP FOREIGN KEY FK_A79BC17E38B217A7');
        $this->addSql('ALTER TABLE publication_like DROP FOREIGN KEY FK_A79BC17EA76ED395');
    }
}
