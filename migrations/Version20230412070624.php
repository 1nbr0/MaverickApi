<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230412070624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flight_schedule ADD departure_time DATETIME NOT NULL, ADD arrival_time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE track ADD flight_schedule_departure_id INT NOT NULL, ADD flight_schedule_arrival_id INT NOT NULL');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A6D6E6629C FOREIGN KEY (flight_schedule_departure_id) REFERENCES flight_schedule (id)');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A67D81D365 FOREIGN KEY (flight_schedule_arrival_id) REFERENCES flight_schedule (id)');
        $this->addSql('CREATE INDEX IDX_D6E3F8A6D6E6629C ON track (flight_schedule_departure_id)');
        $this->addSql('CREATE INDEX IDX_D6E3F8A67D81D365 ON track (flight_schedule_arrival_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A6D6E6629C');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A67D81D365');
        $this->addSql('DROP INDEX IDX_D6E3F8A6D6E6629C ON track');
        $this->addSql('DROP INDEX IDX_D6E3F8A67D81D365 ON track');
        $this->addSql('ALTER TABLE track DROP flight_schedule_departure_id, DROP flight_schedule_arrival_id');
        $this->addSql('ALTER TABLE flight_schedule DROP departure_time, DROP arrival_time');
    }
}
