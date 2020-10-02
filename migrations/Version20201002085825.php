<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201002085825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE housing ADD type_id INT NOT NULL, ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE housing ADD CONSTRAINT FK_FB8142C3C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE housing ADD CONSTRAINT FK_FB8142C36BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_FB8142C3C54C8C93 ON housing (type_id)');
        $this->addSql('CREATE INDEX IDX_FB8142C36BF700BD ON housing (status_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE housing DROP FOREIGN KEY FK_FB8142C3C54C8C93');
        $this->addSql('ALTER TABLE housing DROP FOREIGN KEY FK_FB8142C36BF700BD');
        $this->addSql('DROP INDEX IDX_FB8142C3C54C8C93 ON housing');
        $this->addSql('DROP INDEX IDX_FB8142C36BF700BD ON housing');
        $this->addSql('ALTER TABLE housing DROP type_id, DROP status_id');
    }
}
