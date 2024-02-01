<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201013420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dessert (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, picture VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_79291B96CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, picture VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9EF68E9CCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE starter (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, picture VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_4042238BCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dessert ADD CONSTRAINT FK_79291B96CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9CCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE starter ADD CONSTRAINT FK_4042238BCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dessert DROP FOREIGN KEY FK_79291B96CCD7E912');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9CCCD7E912');
        $this->addSql('ALTER TABLE starter DROP FOREIGN KEY FK_4042238BCCD7E912');
        $this->addSql('DROP TABLE dessert');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE starter');
    }
}
