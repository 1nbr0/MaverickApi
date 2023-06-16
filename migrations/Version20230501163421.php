<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501163421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warplane ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE warplane ADD CONSTRAINT FK_AF0D76E67E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AF0D76E67E3C61F9 ON warplane (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warplane DROP FOREIGN KEY FK_AF0D76E67E3C61F9');
        $this->addSql('DROP INDEX IDX_AF0D76E67E3C61F9 ON warplane');
        $this->addSql('ALTER TABLE warplane DROP owner_id');
    }
}
