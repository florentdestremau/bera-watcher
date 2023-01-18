<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118072312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bera ADD xml_link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE bera ADD hash VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE subscriber ALTER weekdays DROP DEFAULT');

        $this->addSql('UPDATE bera SET xml_link = REPLACE(link, \'pdf\', \'xml\')'); // csv link
        $this->addSql("UPDATE bera SET hash = substring(link from char_length(link)-17 for 14)");

        $this->addSql('ALTER TABLE bera ALTER xml_link SET NOT NULL');
        $this->addSql('ALTER TABLE bera ALTER hash SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bera DROP xml_link');
        $this->addSql('ALTER TABLE bera DROP hash');
        $this->addSql('ALTER TABLE subscriber ALTER weekdays SET DEFAULT \'LUN,MAR,MER,JEU,VEN,SAM,DIM\'');
    }
}
