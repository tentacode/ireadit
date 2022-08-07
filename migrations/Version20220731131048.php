<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220731131048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE link ADD image_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE link ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE link ALTER creation_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE link ALTER creation_date DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN link.creation_date IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('ALTER TABLE "user" ALTER id SET DEFAULT nextval(\'user_id_seq\')');
        $this->addSql('ALTER TABLE "link_event" ALTER id SET DEFAULT nextval(\'link_event_id_seq\')');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE link DROP image_url');
        $this->addSql('ALTER TABLE link DROP description');
        $this->addSql('ALTER TABLE link ALTER creation_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE link ALTER creation_date DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN link.creation_date IS NULL');
    }
}
