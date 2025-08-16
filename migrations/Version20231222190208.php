<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231222190208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trade CHANGE quantity quantity DOUBLE PRECISION DEFAULT NULL, CHANGE quantity_native quantity_native DOUBLE PRECISION DEFAULT NULL, CHANGE unit_price unit_price DOUBLE PRECISION DEFAULT NULL, CHANGE unit_price_native unit_price_native DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trade CHANGE quantity quantity DOUBLE PRECISION NOT NULL, CHANGE quantity_native quantity_native DOUBLE PRECISION NOT NULL, CHANGE unit_price unit_price DOUBLE PRECISION NOT NULL, CHANGE unit_price_native unit_price_native DOUBLE PRECISION NOT NULL');
    }
}
