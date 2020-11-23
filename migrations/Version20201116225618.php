<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116225618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, api_key_id INT NOT NULL, date DATE NOT NULL, action LONGTEXT NOT NULL, INDEX IDX_8F3F68C58BE312B3 (api_key_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C58BE312B3 FOREIGN KEY (api_key_id) REFERENCES api_key (id)');
        $this->addSql('ALTER TABLE invite ADD valid_until DATETIME NOT NULL, ADD roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD email LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE log');
        $this->addSql('ALTER TABLE invite DROP valid_until, DROP roles, DROP email');
    }
}
