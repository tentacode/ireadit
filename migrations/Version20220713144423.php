<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713144423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE link_event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE link_event (id INT NOT NULL, author_id INT NOT NULL, link_id INT NOT NULL, event_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_94155679F675F31B ON link_event (author_id)');
        $this->addSql('CREATE INDEX IDX_94155679ADA40271 ON link_event (link_id)');
        $this->addSql('ALTER TABLE link_event ADD CONSTRAINT FK_94155679F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE link_event ADD CONSTRAINT FK_94155679ADA40271 FOREIGN KEY (link_id) REFERENCES link (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE link ADD creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER registration_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ALTER registration_date DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN "user".registration_date IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE link_event_id_seq CASCADE');
        $this->addSql('DROP TABLE link_event');
        $this->addSql('ALTER TABLE link DROP creation_date');
        $this->addSql('ALTER TABLE "user" ALTER registration_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ALTER registration_date DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN "user".registration_date IS NULL');
    }
}
