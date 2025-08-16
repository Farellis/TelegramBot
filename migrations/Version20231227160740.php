<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231227160740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position ADD fees_app DOUBLE PRECISION DEFAULT NULL, DROP profit, DROP fees');
        $this->addSql('ALTER TABLE trade DROP unit_price');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position ADD fees DOUBLE PRECISION DEFAULT NULL, CHANGE fees_app profit DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE trade ADD unit_price DOUBLE PRECISION DEFAULT NULL');
    }
}
