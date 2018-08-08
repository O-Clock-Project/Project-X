<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180808081645 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vote ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE promotion_link ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE promotion ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE announcement_type ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE speciality ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE support ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE role ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tag ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE app_users ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE locale ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE warning_bookmark ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE announcement ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE difficulty ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE affectation ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE bookmark_faved RENAME INDEX idx_d649a4b692741d25 TO IDX_DE64CD1092741D25');
        $this->addSql('ALTER TABLE bookmark_faved RENAME INDEX idx_d649a4b6a76ed395 TO IDX_DE64CD10A76ED395');
        $this->addSql('ALTER TABLE bookmark_certified RENAME INDEX idx_88b1a0b192741d25 TO IDX_8946C41A92741D25');
        $this->addSql('ALTER TABLE bookmark_certified RENAME INDEX idx_88b1a0b1a76ed395 TO IDX_8946C41AA76ED395');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE affectation DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE announcement DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE announcement_type DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE app_users DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE bookmark_certified RENAME INDEX idx_8946c41a92741d25 TO IDX_88B1A0B192741D25');
        $this->addSql('ALTER TABLE bookmark_certified RENAME INDEX idx_8946c41aa76ed395 TO IDX_88B1A0B1A76ED395');
        $this->addSql('ALTER TABLE bookmark_faved RENAME INDEX idx_de64cd1092741d25 TO IDX_D649A4B692741D25');
        $this->addSql('ALTER TABLE bookmark_faved RENAME INDEX idx_de64cd10a76ed395 TO IDX_D649A4B6A76ED395');
        $this->addSql('ALTER TABLE comment DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE difficulty DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE locale DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE promotion DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE promotion_link DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE role DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE speciality DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE support DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE tag DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE vote DROP created_at, DROP updated_at, DROP is_active');
        $this->addSql('ALTER TABLE warning_bookmark DROP created_at, DROP updated_at, DROP is_active');
    }
}
