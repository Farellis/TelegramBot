<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219185253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token ADD fdv DOUBLE PRECISION DEFAULT NULL, ADD chart VARCHAR(255) DEFAULT NULL, ADD pair_address VARCHAR(255) DEFAULT NULL, ADD txns LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD volume LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD price_change LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD liquidity LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD price DOUBLE PRECISION DEFAULT NULL, ADD price_native DOUBLE PRECISION DEFAULT NULL, ADD network VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE token DROP fdv, DROP chart, DROP pair_address, DROP txns, DROP volume, DROP price_change, DROP liquidity, DROP price, DROP price_native, DROP network');
    }
}
