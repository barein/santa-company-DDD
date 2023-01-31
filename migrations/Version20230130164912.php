<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130164912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add StoredEvent.dispatched';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE stored_event ADD dispatched TINYINT(1) NOT NULL AFTER ulid');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE stored_event DROP dispatched');
    }
}
