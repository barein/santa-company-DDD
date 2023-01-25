<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125143501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE child_child_watching (id INT AUTO_INCREMENT NOT NULL, ulid VARCHAR(26) NOT NULL COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_49D673A7C288C859 (ulid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE child_letter_processing (id INT AUTO_INCREMENT NOT NULL, ulid VARCHAR(26) NOT NULL COMMENT \'(DC2Type:ulid)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address_number INT NOT NULL, address_street VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, address_zip_code INT NOT NULL, address_iso_country_code VARCHAR(3) NOT NULL, UNIQUE INDEX UNIQ_BC75DFEAC288C859 (ulid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gift_request (id INT AUTO_INCREMENT NOT NULL, letter_id INT NOT NULL, ulid VARCHAR(26) NOT NULL COMMENT \'(DC2Type:ulid)\', gift_name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_57CDBC30C288C859 (ulid), INDEX IDX_57CDBC304525FF26 (letter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE letter (id INT AUTO_INCREMENT NOT NULL, child_id INT NOT NULL, ulid VARCHAR(26) NOT NULL COMMENT \'(DC2Type:ulid)\', receiving_date DATE NOT NULL, sender_address_number INT NOT NULL, sender_address_street VARCHAR(255) NOT NULL, sender_address_city VARCHAR(255) NOT NULL, sender_address_zip_code INT NOT NULL, sender_address_iso_country_code VARCHAR(3) NOT NULL, UNIQUE INDEX UNIQ_8E02EE0AC288C859 (ulid), INDEX IDX_8E02EE0ADD62C21B (child_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gift_request ADD CONSTRAINT FK_57CDBC304525FF26 FOREIGN KEY (letter_id) REFERENCES letter (id)');
        $this->addSql('ALTER TABLE letter ADD CONSTRAINT FK_8E02EE0ADD62C21B FOREIGN KEY (child_id) REFERENCES child_letter_processing (id)');
        $this->addSql('DROP TABLE child');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE child (id INT AUTO_INCREMENT NOT NULL, ulid VARCHAR(26) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_22B35429C288C859 (ulid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE gift_request DROP FOREIGN KEY FK_57CDBC304525FF26');
        $this->addSql('ALTER TABLE letter DROP FOREIGN KEY FK_8E02EE0ADD62C21B');
        $this->addSql('DROP TABLE child_child_watching');
        $this->addSql('DROP TABLE child_letter_processing');
        $this->addSql('DROP TABLE gift_request');
        $this->addSql('DROP TABLE letter');
    }
}
