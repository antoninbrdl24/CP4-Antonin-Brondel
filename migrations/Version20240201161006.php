<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201161006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dessert ADD allergens JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE meal ADD allergens JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE starter ADD allergens JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE starter DROP allergens');
        $this->addSql('ALTER TABLE dessert DROP allergens');
        $this->addSql('ALTER TABLE meal DROP allergens');
    }
}
