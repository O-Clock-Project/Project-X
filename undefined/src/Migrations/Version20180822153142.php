<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180822153142 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invitation ADD promotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_F11D61A2139DF194 ON invitation (promotion_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A2139DF194');
        $this->addSql('DROP INDEX IDX_F11D61A2139DF194 ON invitation');
        $this->addSql('ALTER TABLE invitation DROP promotion_id');
    }
}
