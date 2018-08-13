<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180813152908 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, bookmark_id INT NOT NULL, voter_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, value INT NOT NULL, INDEX IDX_5A10856492741D25 (bookmark_id), INDEX IDX_5A108564EBB4B8AD (voter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_link (id INT AUTO_INCREMENT NOT NULL, promotion_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(64) NOT NULL, url VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, INDEX IDX_8056116E139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_announcement (promotion_id INT NOT NULL, announcement_id INT NOT NULL, INDEX IDX_2B6E1C1F139DF194 (promotion_id), INDEX IDX_2B6E1C1F913AEA17 (announcement_id), PRIMARY KEY(promotion_id, announcement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE announcement_type (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speciality (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE support (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(64) NOT NULL, code VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, label VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, speciality_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, username VARCHAR(128) NOT NULL, first_name VARCHAR(128) NOT NULL, last_name VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, password VARCHAR(255) NOT NULL, pseudo_github VARCHAR(255) NOT NULL, zip INT NOT NULL, birthday DATETIME NOT NULL, INDEX IDX_C25028243B5A08D7 (speciality_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locale (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, announcement_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, body LONGTEXT NOT NULL, banned TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C913AEA17 (announcement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warning_bookmark (id INT AUTO_INCREMENT NOT NULL, bookmark_id INT NOT NULL, author_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_5C3C084992741D25 (bookmark_id), INDEX IDX_5C3C0849F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE announcement (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, type_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, frozen TINYINT(1) NOT NULL, closing_at DATETIME NOT NULL, INDEX IDX_4DB9D91CF675F31B (author_id), INDEX IDX_4DB9D91CC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE difficulty (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, name VARCHAR(64) NOT NULL, level INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE affectation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, promotion_id INT DEFAULT NULL, role_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_F4DD61D3A76ED395 (user_id), INDEX IDX_F4DD61D3139DF194 (promotion_id), INDEX IDX_F4DD61D3D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmark (id INT AUTO_INCREMENT NOT NULL, support_id INT DEFAULT NULL, difficulty_id INT DEFAULT NULL, user_id INT DEFAULT NULL, locale_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, title VARCHAR(255) NOT NULL, resume LONGTEXT NOT NULL, url VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, banned TINYINT(1) DEFAULT \'0\' NOT NULL, published_at DATETIME NOT NULL, author VARCHAR(128) NOT NULL, INDEX IDX_DA62921D315B405 (support_id), INDEX IDX_DA62921DFCFA9DAE (difficulty_id), INDEX IDX_DA62921DA76ED395 (user_id), INDEX IDX_DA62921DE559DFD1 (locale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmark_faved (bookmark_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DE64CD1092741D25 (bookmark_id), INDEX IDX_DE64CD10A76ED395 (user_id), PRIMARY KEY(bookmark_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmark_certified (bookmark_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8946C41A92741D25 (bookmark_id), INDEX IDX_8946C41AA76ED395 (user_id), PRIMARY KEY(bookmark_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmark_tag (bookmark_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_23CB7F4A92741D25 (bookmark_id), INDEX IDX_23CB7F4ABAD26311 (tag_id), PRIMARY KEY(bookmark_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856492741D25 FOREIGN KEY (bookmark_id) REFERENCES bookmark (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564EBB4B8AD FOREIGN KEY (voter_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE promotion_link ADD CONSTRAINT FK_8056116E139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion_announcement ADD CONSTRAINT FK_2B6E1C1F139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_announcement ADD CONSTRAINT FK_2B6E1C1F913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_users ADD CONSTRAINT FK_C25028243B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id)');
        $this->addSql('ALTER TABLE warning_bookmark ADD CONSTRAINT FK_5C3C084992741D25 FOREIGN KEY (bookmark_id) REFERENCES bookmark (id)');
        $this->addSql('ALTER TABLE warning_bookmark ADD CONSTRAINT FK_5C3C0849F675F31B FOREIGN KEY (author_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE announcement ADD CONSTRAINT FK_4DB9D91CF675F31B FOREIGN KEY (author_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE announcement ADD CONSTRAINT FK_4DB9D91CC54C8C93 FOREIGN KEY (type_id) REFERENCES announcement_type (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE bookmark ADD CONSTRAINT FK_DA62921D315B405 FOREIGN KEY (support_id) REFERENCES support (id)');
        $this->addSql('ALTER TABLE bookmark ADD CONSTRAINT FK_DA62921DFCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulty (id)');
        $this->addSql('ALTER TABLE bookmark ADD CONSTRAINT FK_DA62921DA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE bookmark ADD CONSTRAINT FK_DA62921DE559DFD1 FOREIGN KEY (locale_id) REFERENCES locale (id)');
        $this->addSql('ALTER TABLE bookmark_faved ADD CONSTRAINT FK_DE64CD1092741D25 FOREIGN KEY (bookmark_id) REFERENCES bookmark (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmark_faved ADD CONSTRAINT FK_DE64CD10A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmark_certified ADD CONSTRAINT FK_8946C41A92741D25 FOREIGN KEY (bookmark_id) REFERENCES bookmark (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmark_certified ADD CONSTRAINT FK_8946C41AA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmark_tag ADD CONSTRAINT FK_23CB7F4A92741D25 FOREIGN KEY (bookmark_id) REFERENCES bookmark (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmark_tag ADD CONSTRAINT FK_23CB7F4ABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_link DROP FOREIGN KEY FK_8056116E139DF194');
        $this->addSql('ALTER TABLE promotion_announcement DROP FOREIGN KEY FK_2B6E1C1F139DF194');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3139DF194');
        $this->addSql('ALTER TABLE announcement DROP FOREIGN KEY FK_4DB9D91CC54C8C93');
        $this->addSql('ALTER TABLE app_users DROP FOREIGN KEY FK_C25028243B5A08D7');
        $this->addSql('ALTER TABLE bookmark DROP FOREIGN KEY FK_DA62921D315B405');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3D60322AC');
        $this->addSql('ALTER TABLE bookmark_tag DROP FOREIGN KEY FK_23CB7F4ABAD26311');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564EBB4B8AD');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE warning_bookmark DROP FOREIGN KEY FK_5C3C0849F675F31B');
        $this->addSql('ALTER TABLE announcement DROP FOREIGN KEY FK_4DB9D91CF675F31B');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3A76ED395');
        $this->addSql('ALTER TABLE bookmark DROP FOREIGN KEY FK_DA62921DA76ED395');
        $this->addSql('ALTER TABLE bookmark_faved DROP FOREIGN KEY FK_DE64CD10A76ED395');
        $this->addSql('ALTER TABLE bookmark_certified DROP FOREIGN KEY FK_8946C41AA76ED395');
        $this->addSql('ALTER TABLE bookmark DROP FOREIGN KEY FK_DA62921DE559DFD1');
        $this->addSql('ALTER TABLE promotion_announcement DROP FOREIGN KEY FK_2B6E1C1F913AEA17');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C913AEA17');
        $this->addSql('ALTER TABLE bookmark DROP FOREIGN KEY FK_DA62921DFCFA9DAE');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A10856492741D25');
        $this->addSql('ALTER TABLE warning_bookmark DROP FOREIGN KEY FK_5C3C084992741D25');
        $this->addSql('ALTER TABLE bookmark_faved DROP FOREIGN KEY FK_DE64CD1092741D25');
        $this->addSql('ALTER TABLE bookmark_certified DROP FOREIGN KEY FK_8946C41A92741D25');
        $this->addSql('ALTER TABLE bookmark_tag DROP FOREIGN KEY FK_23CB7F4A92741D25');
        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP TABLE promotion_link');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_announcement');
        $this->addSql('DROP TABLE announcement_type');
        $this->addSql('DROP TABLE speciality');
        $this->addSql('DROP TABLE support');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE locale');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE warning_bookmark');
        $this->addSql('DROP TABLE announcement');
        $this->addSql('DROP TABLE difficulty');
        $this->addSql('DROP TABLE affectation');
        $this->addSql('DROP TABLE bookmark');
        $this->addSql('DROP TABLE bookmark_faved');
        $this->addSql('DROP TABLE bookmark_certified');
        $this->addSql('DROP TABLE bookmark_tag');
    }
}
