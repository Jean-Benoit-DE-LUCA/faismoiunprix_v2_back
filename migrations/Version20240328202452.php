<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328202452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EDE18E50B');
        $this->addSql('DROP INDEX IDX_29D6873EDE18E50B ON offer');
        $this->addSql('ALTER TABLE offer CHANGE product_id_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873ECB147C66 FOREIGN KEY (user_offer) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_29D6873E4584665A ON offer (product_id)');
        $this->addSql('CREATE INDEX IDX_29D6873ECB147C66 ON offer (user_offer)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E4584665A');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873ECB147C66');
        $this->addSql('DROP INDEX IDX_29D6873E4584665A ON offer');
        $this->addSql('DROP INDEX IDX_29D6873ECB147C66 ON offer');
        $this->addSql('ALTER TABLE offer CHANGE product_id product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_29D6873EDE18E50B ON offer (product_id_id)');
    }
}
