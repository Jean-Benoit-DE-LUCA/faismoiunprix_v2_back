<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419110243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session_data CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE session_data ADD CONSTRAINT FK_6B82F349A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6B82F349A76ED395 ON session_data (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session_data DROP FOREIGN KEY FK_6B82F349A76ED395');
        $this->addSql('DROP INDEX IDX_6B82F349A76ED395 ON session_data');
        $this->addSql('ALTER TABLE session_data CHANGE user_id user_id INT NOT NULL');
    }
}
