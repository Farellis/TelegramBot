<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231222185859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position CHANGE amount amount DOUBLE PRECISION DEFAULT NULL, CHANGE initial initial DOUBLE PRECISION DEFAULT NULL, CHANGE profit profit DOUBLE PRECISION DEFAULT NULL, CHANGE profit_native profit_native DOUBLE PRECISION DEFAULT NULL, CHANGE fees fees DOUBLE PRECISION DEFAULT NULL, CHANGE fees_native fees_native DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE initial initial DOUBLE PRECISION NOT NULL, CHANGE profit profit DOUBLE PRECISION NOT NULL, CHANGE profit_native profit_native DOUBLE PRECISION NOT NULL, CHANGE fees fees DOUBLE PRECISION NOT NULL, CHANGE fees_native fees_native DOUBLE PRECISION NOT NULL');
    }
}
