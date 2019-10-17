<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191013223431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_on DATETIME NOT NULL, category VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_visible TINYINT(1) NOT NULL, start_time DATETIME DEFAULT NULL, end_time DATETIME DEFAULT NULL, course_location VARCHAR(255) DEFAULT NULL, is_public TINYINT(1) NOT NULL, INDEX IDX_169E6FB9B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CourseManagedbyUsers (course_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_808F5632591CC992 (course_id), INDEX IDX_808F5632A76ED395 (user_id), PRIMARY KEY(course_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE StudentsInCourses (course_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B681BFF1591CC992 (course_id), INDEX IDX_B681BFF1A76ED395 (user_id), PRIMARY KEY(course_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joint_file (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, course_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, file_location LONGTEXT DEFAULT NULL, created_on DATETIME NOT NULL, download_count INT NOT NULL, INDEX IDX_3593C053B03A8386 (created_by_id), INDEX IDX_3593C053591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE help (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, user_id INT DEFAULT NULL, is_needed TINYINT(1) NOT NULL, INDEX IDX_8875CAC591CC992 (course_id), INDEX IDX_8875CACA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pad (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, content LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_9D894EE5591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, secondname VARCHAR(255) DEFAULT NULL, profile_picture LONGTEXT DEFAULT NULL, affiliation VARCHAR(255) DEFAULT NULL, occupation VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, linked_in VARCHAR(255) NOT NULL, facebook VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, other_social_media VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, orcid VARCHAR(255) DEFAULT NULL, created_on DATE NOT NULL, gender VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8157AA0FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_state (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, is_online TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_415129A3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE CourseManagedbyUsers ADD CONSTRAINT FK_808F5632591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE CourseManagedbyUsers ADD CONSTRAINT FK_808F5632A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE StudentsInCourses ADD CONSTRAINT FK_B681BFF1591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE StudentsInCourses ADD CONSTRAINT FK_B681BFF1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE joint_file ADD CONSTRAINT FK_3593C053B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE joint_file ADD CONSTRAINT FK_3593C053591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE help ADD CONSTRAINT FK_8875CAC591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE help ADD CONSTRAINT FK_8875CACA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pad ADD CONSTRAINT FK_9D894EE5591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_state ADD CONSTRAINT FK_415129A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE CourseManagedbyUsers DROP FOREIGN KEY FK_808F5632591CC992');
        $this->addSql('ALTER TABLE StudentsInCourses DROP FOREIGN KEY FK_B681BFF1591CC992');
        $this->addSql('ALTER TABLE joint_file DROP FOREIGN KEY FK_3593C053591CC992');
        $this->addSql('ALTER TABLE help DROP FOREIGN KEY FK_8875CAC591CC992');
        $this->addSql('ALTER TABLE pad DROP FOREIGN KEY FK_9D894EE5591CC992');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9B03A8386');
        $this->addSql('ALTER TABLE CourseManagedbyUsers DROP FOREIGN KEY FK_808F5632A76ED395');
        $this->addSql('ALTER TABLE StudentsInCourses DROP FOREIGN KEY FK_B681BFF1A76ED395');
        $this->addSql('ALTER TABLE joint_file DROP FOREIGN KEY FK_3593C053B03A8386');
        $this->addSql('ALTER TABLE help DROP FOREIGN KEY FK_8875CACA76ED395');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FA76ED395');
        $this->addSql('ALTER TABLE user_state DROP FOREIGN KEY FK_415129A3A76ED395');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE CourseManagedbyUsers');
        $this->addSql('DROP TABLE StudentsInCourses');
        $this->addSql('DROP TABLE joint_file');
        $this->addSql('DROP TABLE help');
        $this->addSql('DROP TABLE pad');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE user_state');
        $this->addSql('DROP TABLE user');
    }
}
