<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118075107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bera ADD xml TEXT DEFAULT NULL');

        $beras = $this->connection->executeQuery('select * from bera')->fetchAllAssociative();

        foreach ($beras as $bera) {
            $xml = file_get_contents($bera['xml_link']);
            $this->addSql('update bera set xml = :xml where id = :id', ['xml' => $xml, 'id' => $bera['id']]);
        }

        $this->addSql('ALTER TABLE bera ALTER xml SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bera DROP xml');
    }

    public function postUp(Schema $schema): void
    {
    }
}
