<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180712143018 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonce ADD creator_id_id INT NOT NULL, ADD creation_date DATETIME NOT NULL, DROP price');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5F05788E9 FOREIGN KEY (creator_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F65593E5F05788E9 ON annonce (creator_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5F05788E9');
        $this->addSql('DROP INDEX IDX_F65593E5F05788E9 ON annonce');
        $this->addSql('ALTER TABLE annonce ADD price DOUBLE PRECISION NOT NULL, DROP creator_id_id, DROP creation_date');
    }
}
