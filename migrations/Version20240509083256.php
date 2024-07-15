<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509083256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_contact ADD CONSTRAINT FK_DCEADC34EBDEAB20 FOREIGN KEY (user_receive_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DCEADC34EBDEAB20 ON message_contact (user_receive_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_contact DROP FOREIGN KEY FK_DCEADC34EBDEAB20');
        $this->addSql('DROP INDEX IDX_DCEADC34EBDEAB20 ON message_contact');
    }
}
