<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190204112202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if ('mysql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE category_dossier (category_id INT NOT NULL, dossier_id INT NOT NULL, INDEX IDX_FA90A04912469DE2 (category_id), INDEX IDX_FA90A049611C0C56 (dossier_id), PRIMARY KEY(category_id, dossier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(100) DEFAULT NULL, city VARCHAR(50) DEFAULT NULL, country VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE dossier (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_date DATETIME NOT NULL, active TINYINT(1) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_3D48E03719EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('ALTER TABLE category_dossier ADD CONSTRAINT FK_FA90A04912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE category_dossier ADD CONSTRAINT FK_FA90A049611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E03719EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');

            // Second migration
            $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('ALTER TABLE dossier ADD author_id INT NOT NULL');
            $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E037F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
            $this->addSql('CREATE INDEX IDX_3D48E037F675F31B ON dossier (author_id)');
        } elseif ('sqlite' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL)');
            $this->addSql('CREATE TABLE category_dossier (category_id INTEGER NOT NULL, dossier_id INTEGER NOT NULL, PRIMARY KEY(category_id, dossier_id))');
            $this->addSql('CREATE INDEX IDX_FA90A04912469DE2 ON category_dossier (category_id)');
            $this->addSql('CREATE INDEX IDX_FA90A049611C0C56 ON category_dossier (dossier_id)');
            $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(100) DEFAULT NULL, city VARCHAR(50) DEFAULT NULL, country VARCHAR(50) DEFAULT NULL)');
            $this->addSql('CREATE TABLE dossier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, created_date DATETIME NOT NULL, active BOOLEAN NOT NULL, content CLOB NOT NULL)');
            $this->addSql('CREATE INDEX IDX_3D48E03719EB6921 ON dossier (client_id)');

            // Second migration
            $this->addSql(
                'CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:simple_array)
        , enabled BOOLEAN NOT NULL)'
            );
            $this->addSql('DROP INDEX IDX_3D48E03719EB6921');
            $this->addSql('CREATE TEMPORARY TABLE __temp__dossier AS SELECT id, client_id, title, created_date, active, content FROM dossier');
            $this->addSql('DROP TABLE dossier');
            $this->addSql('CREATE TABLE dossier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, created_date DATETIME NOT NULL, active BOOLEAN NOT NULL, content CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_3D48E03719EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3D48E037F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
            $this->addSql('INSERT INTO dossier (id, client_id, title, created_date, active, content) SELECT id, client_id, title, created_date, active, content FROM __temp__dossier');
            $this->addSql('DROP TABLE __temp__dossier');
            $this->addSql('CREATE INDEX IDX_3D48E03719EB6921 ON dossier (client_id)');
            $this->addSql('CREATE INDEX IDX_3D48E037F675F31B ON dossier (author_id)');
            $this->addSql('DROP INDEX IDX_FA90A049611C0C56');
            $this->addSql('DROP INDEX IDX_FA90A04912469DE2');
            $this->addSql('CREATE TEMPORARY TABLE __temp__category_dossier AS SELECT category_id, dossier_id FROM category_dossier');
            $this->addSql('DROP TABLE category_dossier');
            $this->addSql('CREATE TABLE category_dossier (category_id INTEGER NOT NULL, dossier_id INTEGER NOT NULL, PRIMARY KEY(category_id, dossier_id), CONSTRAINT FK_FA90A04912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FA90A049611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
            $this->addSql('INSERT INTO category_dossier (category_id, dossier_id) SELECT category_id, dossier_id FROM __temp__category_dossier');
            $this->addSql('DROP TABLE __temp__category_dossier');
            $this->addSql('CREATE INDEX IDX_FA90A049611C0C56 ON category_dossier (dossier_id)');
            $this->addSql('CREATE INDEX IDX_FA90A04912469DE2 ON category_dossier (category_id)');
        } else {
            $this->abortIf(true, 'Migration can only be executed safely on \'mysql\' or \'sqlite\'.');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        if ('mysql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE category_dossier DROP FOREIGN KEY FK_FA90A04912469DE2');
            $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E03719EB6921');
            $this->addSql('ALTER TABLE category_dossier DROP FOREIGN KEY FK_FA90A049611C0C56');
            $this->addSql('DROP TABLE category');
            $this->addSql('DROP TABLE category_dossier');
            $this->addSql('DROP TABLE client');
            $this->addSql('DROP TABLE dossier');
            $this->addSql('DROP TABLE user');
        } elseif ('sqlite' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP TABLE category');
            $this->addSql('DROP TABLE category_dossier');
            $this->addSql('DROP TABLE client');
            $this->addSql('DROP TABLE dossier');
            $this->addSql('DROP TABLE user');
        } else {
            $this->abortIf(true, 'Migration can only be executed safely on \'mysql\' or \'sqlite\'.');
        }
    }
}
