<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422131616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE user_offer_id user_offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB34B90EE FOREIGN KEY (user_offer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FB34B90EE ON message (user_offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB34B90EE');
        $this->addSql('DROP INDEX IDX_B6BD307FB34B90EE ON message');
        $this->addSql('ALTER TABLE message CHANGE user_offer_id user_offer_id INT NOT NULL');
    }
}
