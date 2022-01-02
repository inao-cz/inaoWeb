<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220102170249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_key (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, api_key VARCHAR(254) NOT NULL, UNIQUE INDEX UNIQ_C912ED9D7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE captcha (id INT AUTO_INCREMENT NOT NULL, discord_id BIGINT NOT NULL, captcha_id VARCHAR(255) NOT NULL, solved TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, uploaded DATE NOT NULL, name VARCHAR(255) NOT NULL, ext VARCHAR(10) NOT NULL, INDEX IDX_C53D045F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invite (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, used_by_id INT DEFAULT NULL, code VARCHAR(128) NOT NULL, valid_until DATETIME NOT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', email LONGTEXT NOT NULL, INDEX IDX_C7E210D77E3C61F9 (owner_id), UNIQUE INDEX UNIQ_C7E210D74C2B72A8 (used_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE links (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name LONGTEXT NOT NULL, target LONGTEXT NOT NULL, public TINYINT(1) NOT NULL, redirects INT NOT NULL, INDEX IDX_D182A1187E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, date DATETIME NOT NULL, action LONGTEXT NOT NULL, INDEX IDX_8F3F68C57E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_key ADD CONSTRAINT FK_C912ED9D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE invite ADD CONSTRAINT FK_C7E210D77E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE invite ADD CONSTRAINT FK_C7E210D74C2B72A8 FOREIGN KEY (used_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A1187E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C57E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_key DROP FOREIGN KEY FK_C912ED9D7E3C61F9');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F7E3C61F9');
        $this->addSql('ALTER TABLE invite DROP FOREIGN KEY FK_C7E210D77E3C61F9');
        $this->addSql('ALTER TABLE invite DROP FOREIGN KEY FK_C7E210D74C2B72A8');
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A1187E3C61F9');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C57E3C61F9');
        $this->addSql('DROP TABLE api_key');
        $this->addSql('DROP TABLE captcha');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE invite');
        $this->addSql('DROP TABLE links');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE `user`');
    }
}
