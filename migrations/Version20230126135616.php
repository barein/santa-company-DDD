<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230126135616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change Letter.receivingDate from DateTime to DateTimeImmutable';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE child_of_child_watching RENAME INDEX uniq_49d673a7c288c859 TO UNIQ_520FD332C288C859');
        $this->addSql('ALTER TABLE child_of_letter_processing RENAME INDEX uniq_bc75dfeac288c859 TO UNIQ_16A57373C288C859');
        $this->addSql('ALTER TABLE letter CHANGE receiving_date receiving_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE letter CHANGE receiving_date receiving_date DATE NOT NULL');
        $this->addSql('ALTER TABLE child_of_child_watching RENAME INDEX uniq_520fd332c288c859 TO UNIQ_49D673A7C288C859');
        $this->addSql('ALTER TABLE child_of_letter_processing RENAME INDEX uniq_16a57373c288c859 TO UNIQ_BC75DFEAC288C859');
    }
}
