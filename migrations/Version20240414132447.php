<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414132447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO category (name) VALUES ("real_estate")');
        $this->addSql('INSERT INTO category (name) VALUES ("automobile")');
        $this->addSql('INSERT INTO category (name) VALUES ("job")');
        $this->addSql('INSERT INTO category (name) VALUES ("electronic")');
        $this->addSql('INSERT INTO category (name) VALUES ("service")');
        $this->addSql('INSERT INTO category (name) VALUES ("pet")');
        $this->addSql('INSERT INTO category (name) VALUES ("fashion")');
        $this->addSql('INSERT INTO category (name) VALUES ("pet")');
        $this->addSql('INSERT INTO category (name) VALUES ("leisure_entertainment")');
        $this->addSql('INSERT INTO category (name) VALUES ("home")');
        $this->addSql('INSERT INTO category (name) VALUES ("garden_outdoor")');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM category WHERE name = "real_estate"');
        $this->addSql('DELETE FROM category WHERE name = "automobile"');
        $this->addSql('DELETE FROM category WHERE name = "job"');
        $this->addSql('DELETE FROM category WHERE name = "electronic"');
        $this->addSql('DELETE FROM category WHERE name = "service"');
        $this->addSql('DELETE FROM category WHERE name = "pet"');
        $this->addSql('DELETE FROM category WHERE name = "fashion"');
        $this->addSql('DELETE FROM category WHERE name = "pet"');
        $this->addSql('DELETE FROM category WHERE name = "leisure_entertainment"');
        $this->addSql('DELETE FROM category WHERE name = "home"');
        $this->addSql('DELETE FROM category WHERE name = "garden_outdoor"');

    }
}
