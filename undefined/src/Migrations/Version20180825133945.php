<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180825133945 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, created_user_id INT DEFAULT NULL, promotion_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, email VARCHAR(128) NOT NULL, secret_code VARCHAR(128) NOT NULL, INDEX IDX_F11D61A2F624B39D (sender_id), UNIQUE INDEX UNIQ_F11D61A2E104C1D3 (created_user_id), INDEX IDX_F11D61A2139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2F624B39D FOREIGN KEY (sender_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2E104C1D3 FOREIGN KEY (created_user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE invitation');
    }
}
