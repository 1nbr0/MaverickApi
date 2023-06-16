<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615140246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flight_schedule ADD CONSTRAINT FK_43D7CB697B927BD8 FOREIGN KEY (owner_of_flight_schedules_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_43D7CB697B927BD8 ON flight_schedule (owner_of_flight_schedules_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flight_schedule DROP FOREIGN KEY FK_43D7CB697B927BD8');
        $this->addSql('DROP INDEX IDX_43D7CB697B927BD8 ON flight_schedule');
    }
}
