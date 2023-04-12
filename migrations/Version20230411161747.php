<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411161747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flight_schedule (id INT AUTO_INCREMENT NOT NULL, assigned_plane_id INT NOT NULL, name VARCHAR(255) NOT NULL, id_flight VARCHAR(255) NOT NULL, INDEX IDX_43D7CB69883B60A1 (assigned_plane_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flight_schedule ADD CONSTRAINT FK_43D7CB69883B60A1 FOREIGN KEY (assigned_plane_id) REFERENCES warplane (id)');
        $this->addSql('ALTER TABLE track ADD airport_id INT NOT NULL');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A6289F53C8 FOREIGN KEY (airport_id) REFERENCES airport (id)');
        $this->addSql('CREATE INDEX IDX_D6E3F8A6289F53C8 ON track (airport_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flight_schedule DROP FOREIGN KEY FK_43D7CB69883B60A1');
        $this->addSql('DROP TABLE flight_schedule');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A6289F53C8');
        $this->addSql('DROP INDEX IDX_D6E3F8A6289F53C8 ON track');
        $this->addSql('ALTER TABLE track DROP airport_id');
    }
}
