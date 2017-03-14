<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170314081152 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE FavouriteSnippet (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE FavouriteStory (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Project (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SnippetStatus (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE snippet ADD snippet_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE snippet ADD CONSTRAINT FK_961C8CD5B83E7194 FOREIGN KEY (snippet_status_id) REFERENCES SnippetStatus (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_961C8CD5B83E7194 ON snippet (snippet_status_id)');
        $this->addSql('ALTER TABLE story ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438166D1F9C FOREIGN KEY (project_id) REFERENCES Project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_EB560438166D1F9C ON story (project_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE story DROP FOREIGN KEY FK_EB560438166D1F9C');
        $this->addSql('ALTER TABLE snippet DROP FOREIGN KEY FK_961C8CD5B83E7194');
        $this->addSql('DROP TABLE FavouriteSnippet');
        $this->addSql('DROP TABLE FavouriteStory');
        $this->addSql('DROP TABLE Project');
        $this->addSql('DROP TABLE SnippetStatus');
        $this->addSql('DROP INDEX IDX_961C8CD5B83E7194 ON snippet');
        $this->addSql('ALTER TABLE snippet DROP snippet_status_id');
        $this->addSql('DROP INDEX IDX_EB560438166D1F9C ON story');
        $this->addSql('ALTER TABLE story DROP project_id');
    }
}
