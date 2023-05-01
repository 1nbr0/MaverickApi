<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426114150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track ADD position_track VARCHAR(5) DEFAULT NULL, ADD has_terminal TINYINT(1) DEFAULT NULL, ADD terminal_name VARCHAR(60) DEFAULT NULL, ADD terminal_number INT DEFAULT NULL, CHANGE number id_track_number INT NOT NULL, CHANGE name track_name_qfu VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track DROP position_track, DROP has_terminal, DROP terminal_name, DROP terminal_number, CHANGE id_track_number number INT NOT NULL, CHANGE track_name_qfu name VARCHAR(50) NOT NULL');
    }
}
