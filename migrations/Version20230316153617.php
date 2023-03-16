<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316153617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add child infos into child_of_child_watching';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            ALTER TABLE child_of_child_watching 
                ADD first_name VARCHAR(255) NOT NULL, 
                ADD last_name VARCHAR(255) NOT NULL, 
                ADD address_number INT NOT NULL, 
                ADD address_street VARCHAR(255) NOT NULL, 
                ADD address_city VARCHAR(255) NOT NULL, 
                ADD address_zip_code INT NOT NULL, 
                ADD address_iso_country_code VARCHAR(3) NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('
            ALTER TABLE child_of_child_watching 
                DROP first_name, 
                DROP last_name, 
                DROP address_number, 
                DROP address_street, 
                DROP address_city, 
                DROP address_zip_code, 
                DROP address_iso_country_code
        ');
    }
}
