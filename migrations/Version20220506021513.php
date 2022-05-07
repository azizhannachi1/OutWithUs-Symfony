<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220506021513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAFFB3979 FOREIGN KEY (publications_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE publication_like ADD CONSTRAINT FK_A79BC17E38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication_like ADD CONSTRAINT FK_A79BC17EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reset_password_request CHANGE expires_at expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE selector selector VARCHAR(20) NOT NULL, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL, CHANGE requested_at requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAFFB3979');
        $this->addSql('ALTER TABLE publication CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE publication_like DROP FOREIGN KEY FK_A79BC17E38B217A7');
        $this->addSql('ALTER TABLE publication_like DROP FOREIGN KEY FK_A79BC17EA76ED395');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(255) NOT NULL, CHANGE hashed_token hashed_token VARCHAR(255) NOT NULL, CHANGE requested_at requested_at DATE NOT NULL, CHANGE expires_at expires_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user DROP reset_token');
    }
}
