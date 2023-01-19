<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119144954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Action entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE action (
                id INT AUTO_INCREMENT NOT NULL, 
                ulid VARCHAR(26) NOT NULL COMMENT \'(DC2Type:ulid)\', 
                child_ulid VARCHAR(26) NOT NULL COMMENT \'(DC2Type:ulid)\', 
                date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
                description VARCHAR(255) NOT NULL, 
                type VARCHAR(255) NOT NULL, 
                UNIQUE INDEX UNIQ_47CC8C92C288C859 (ulid), 
                INDEX child_ulid_index (child_ulid), 
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE action');
    }
}
