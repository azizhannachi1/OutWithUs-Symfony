<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510004340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement_like (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_F30253EFD02F13 (evenement_id), INDEX IDX_F30253EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement_like ADD CONSTRAINT FK_F30253EFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE evenement_like ADD CONSTRAINT FK_F30253EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments DROP userid');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAFFB3979 FOREIGN KEY (publications_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE dislike CHANGE evenement_id evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX userId_fk ON publication');
        $this->addSql('ALTER TABLE publication CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE publication_like ADD CONSTRAINT FK_A79BC17E38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE reclamation CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CE606404A76ED395 ON reclamation (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE evenement_like');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAFFB3979');
        $this->addSql('ALTER TABLE comments ADD userid INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dislike DROP FOREIGN KEY FK_FE3BECAAFD02F13');
        $this->addSql('ALTER TABLE dislike CHANGE evenement_id evenement_id INT NOT NULL');
        $this->addSql('ALTER TABLE evenement CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3FD02F13');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A76ED395');
        $this->addSql('ALTER TABLE publication CHANGE date date DATE DEFAULT NULL');
        $this->addSql('CREATE INDEX userId_fk ON publication (userId)');
        $this->addSql('ALTER TABLE publication_like DROP FOREIGN KEY FK_A79BC17E38B217A7');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('DROP INDEX IDX_CE606404A76ED395 ON reclamation');
        $this->addSql('ALTER TABLE reclamation CHANGE user_id user_id INT DEFAULT NULL');
    }
}
