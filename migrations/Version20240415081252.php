<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415081252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE category SET name = "vehicle" WHERE id = 2');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE category SET name = "automobile" WHERE id = 2');

    }
}
