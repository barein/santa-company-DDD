<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230127145807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create StoredEvent entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE stored_event (
                id INT AUTO_INCREMENT NOT NULL, 
                ulid VARCHAR(26) NOT NULL COMMENT \'(DC2Type:ulid)\', 
                occurred_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
                name VARCHAR(100) NOT NULL, 
                version INT NOT NULL, 
                body LONGTEXT NOT NULL, 
                UNIQUE INDEX UNIQ_7FE58105C288C859 (ulid), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE stored_event');
    }
}
