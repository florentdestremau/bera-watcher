<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221231100040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__subscriber AS SELECT id, email, mountains FROM subscriber');
        $this->addSql('DROP TABLE subscriber');
        $this->addSql('CREATE TABLE subscriber (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, mountains CLOB NOT NULL --(DC2Type:simple_array)
        )');
        $this->addSql('INSERT INTO subscriber (id, email, mountains) SELECT id, email, mountains FROM __temp__subscriber');
        $this->addSql('DROP TABLE __temp__subscriber');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD005B69E7927C74 ON subscriber (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__subscriber AS SELECT id, email, mountains FROM subscriber');
        $this->addSql('DROP TABLE subscriber');
        $this->addSql('CREATE TABLE subscriber (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, mountains CLOB NOT NULL --(DC2Type:simple_array)
        )');
        $this->addSql('INSERT INTO subscriber (id, email, mountains) SELECT id, email, mountains FROM __temp__subscriber');
        $this->addSql('DROP TABLE __temp__subscriber');
    }
}
