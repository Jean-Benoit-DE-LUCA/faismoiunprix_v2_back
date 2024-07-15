<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422143738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jwt DROP FOREIGN KEY FK_8D17CDF09D86650F');
        $this->addSql('DROP INDEX IDX_8D17CDF09D86650F ON jwt');
        $this->addSql('ALTER TABLE jwt CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE jwt ADD CONSTRAINT FK_8D17CDF0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D17CDF0A76ED395 ON jwt (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jwt DROP FOREIGN KEY FK_8D17CDF0A76ED395');
        $this->addSql('DROP INDEX IDX_8D17CDF0A76ED395 ON jwt');
        $this->addSql('ALTER TABLE jwt CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE jwt ADD CONSTRAINT FK_8D17CDF09D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D17CDF09D86650F ON jwt (user_id_id)');
    }
}
