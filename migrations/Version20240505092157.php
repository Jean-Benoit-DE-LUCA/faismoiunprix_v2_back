<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505092157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message_contact (id INT AUTO_INCREMENT NOT NULL, user_product_id INT NOT NULL, user_send_id INT NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', product_id INT NOT NULL, user_receive_id INT NOT NULL, INDEX IDX_DCEADC34E2E3A0B6 (user_product_id), INDEX IDX_DCEADC344B9E2071 (user_send_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message_contact ADD CONSTRAINT FK_DCEADC34E2E3A0B6 FOREIGN KEY (user_product_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message_contact ADD CONSTRAINT FK_DCEADC344B9E2071 FOREIGN KEY (user_send_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_contact DROP FOREIGN KEY FK_DCEADC34E2E3A0B6');
        $this->addSql('ALTER TABLE message_contact DROP FOREIGN KEY FK_DCEADC344B9E2071');
        $this->addSql('DROP TABLE message_contact');
    }
}
