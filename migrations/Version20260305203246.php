<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260305203246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE addresses (id BINARY(16) NOT NULL, street VARCHAR(255) NOT NULL, suite VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zipcode VARCHAR(255) NOT NULL, lat DOUBLE PRECISION DEFAULT NULL, lng DOUBLE PRECISION DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE companies (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, catch_phrase VARCHAR(255) NOT NULL, bs VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE task_event_logs (id BINARY(16) NOT NULL, event_name VARCHAR(255) NOT NULL, message VARCHAR(500) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, task_id BINARY(16) DEFAULT NULL, INDEX IDX_F357C1588DB60186 (task_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE task_history (related_id BINARY(16) NOT NULL, id BINARY(16) NOT NULL, type VARCHAR(10) NOT NULL, date DATETIME NOT NULL, changes JSON NOT NULL, changed_by_id BINARY(16) DEFAULT NULL, related_object_id BINARY(16) DEFAULT NULL, INDEX IDX_385B5AA14162C001 (related_id), INDEX IDX_385B5AA1828AD0A0 (changed_by_id), INDEX IDX_385B5AA18BEC0A30 (related_object_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tasks (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, status SMALLINT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, assigned_user_id BINARY(16) DEFAULT NULL, INDEX IDX_50586597ADF66B1A (assigned_user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE users (id BINARY(16) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, nickname VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, json_placeholder_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, address_id BINARY(16) DEFAULT NULL, company_id BINARY(16) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9920480A1 (json_placeholder_id), INDEX IDX_1483A5E9F5B7AF75 (address_id), INDEX IDX_1483A5E9979B1AD6 (company_id), UNIQUE INDEX users_email_unique (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE task_event_logs ADD CONSTRAINT FK_F357C1588DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE task_history ADD CONSTRAINT FK_385B5AA18BEC0A30 FOREIGN KEY (related_object_id) REFERENCES tasks (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597ADF66B1A FOREIGN KEY (assigned_user_id) REFERENCES users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9F5B7AF75 FOREIGN KEY (address_id) REFERENCES addresses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_event_logs DROP FOREIGN KEY FK_F357C1588DB60186');
        $this->addSql('ALTER TABLE task_history DROP FOREIGN KEY FK_385B5AA18BEC0A30');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597ADF66B1A');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9F5B7AF75');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9979B1AD6');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE task_event_logs');
        $this->addSql('DROP TABLE task_history');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
