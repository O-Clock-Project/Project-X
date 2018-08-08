<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180808113502 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vote CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE promotion_link CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE promotion CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE announcement_type CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE speciality CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE support CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE role CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE tag CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE app_users CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE locale CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE banned banned TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE warning_bookmark CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE announcement CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE difficulty CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE affectation CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE bookmark CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE banned banned TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE affectation CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE announcement CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE announcement_type CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE app_users CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE bookmark CHANGE is_active is_active TINYINT(1) NOT NULL, CHANGE banned banned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE is_active is_active TINYINT(1) NOT NULL, CHANGE banned banned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE difficulty CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE locale CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE promotion CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE promotion_link CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE role CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE speciality CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE support CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tag CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE vote CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE warning_bookmark CHANGE is_active is_active TINYINT(1) NOT NULL');
    }
}
