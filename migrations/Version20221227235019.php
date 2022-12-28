<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221227235019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__bera AS SELECT id, mountain, date, link FROM bera');
        $this->addSql('DROP TABLE bera');
        $this->addSql('CREATE TABLE bera (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, mountain VARCHAR(255) NOT NULL, date DATE NOT NULL, link VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO bera (id, mountain, date, link) SELECT id, mountain, date, link FROM __temp__bera');
        $this->addSql('DROP TABLE __temp__bera');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D9CBA3E59DA2093CAA9E377A ON bera (mountain, date)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__bera AS SELECT id, mountain, date, link FROM bera');
        $this->addSql('DROP TABLE bera');
        $this->addSql('CREATE TABLE bera (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, mountain VARCHAR(255) NOT NULL, date DATE NOT NULL, link VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO bera (id, mountain, date, link) SELECT id, mountain, date, link FROM __temp__bera');
        $this->addSql('DROP TABLE __temp__bera');
    }
}
