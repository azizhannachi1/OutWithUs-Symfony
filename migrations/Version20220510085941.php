<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510085941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAFFB3979 FOREIGN KEY (publications_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE dislike CHANGE evenement_id evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE evenement_like ADD CONSTRAINT FK_F30253EFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE evenement_like ADD CONSTRAINT FK_F30253EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paiement ADD user_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_B1DC7A1EA76ED395 ON paiement (user_id)');
        $this->addSql('DROP INDEX userId_fk ON publication');
        $this->addSql('ALTER TABLE publication CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE publication_like ADD CONSTRAINT FK_A79BC17E38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE reclamation CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CE606404A76ED395 ON reclamation (user_id)');
        $this->addSql('ALTER TABLE reservation_evenement ADD CONSTRAINT FK_11610981FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE reservation_evenement ADD CONSTRAINT FK_11610981A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAFFB3979');
        $this->addSql('ALTER TABLE dislike DROP FOREIGN KEY FK_FE3BECAAFD02F13');
        $this->addSql('ALTER TABLE dislike CHANGE evenement_id evenement_id INT NOT NULL');
        $this->addSql('ALTER TABLE evenement CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement_like DROP FOREIGN KEY FK_F30253EFD02F13');
        $this->addSql('ALTER TABLE evenement_like DROP FOREIGN KEY FK_F30253EA76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3FD02F13');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EFD02F13');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EA76ED395');
        $this->addSql('DROP INDEX IDX_B1DC7A1EA76ED395 ON paiement');
        $this->addSql('ALTER TABLE paiement DROP user_id');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A76ED395');
        $this->addSql('ALTER TABLE publication CHANGE date date DATE DEFAULT NULL');
        $this->addSql('CREATE INDEX userId_fk ON publication (userId)');
        $this->addSql('ALTER TABLE publication_like DROP FOREIGN KEY FK_A79BC17E38B217A7');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('DROP INDEX IDX_CE606404A76ED395 ON reclamation');
        $this->addSql('ALTER TABLE reclamation CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation_evenement DROP FOREIGN KEY FK_11610981FD02F13');
        $this->addSql('ALTER TABLE reservation_evenement DROP FOREIGN KEY FK_11610981A76ED395');
    }
}
