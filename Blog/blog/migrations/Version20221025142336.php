<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025142336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_post DROP FOREIGN KEY FK_D11116CA12469DE2');
        $this->addSql('ALTER TABLE category_post DROP FOREIGN KEY FK_D11116CA4B89032C');
        $this->addSql('DROP TABLE category_post');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_post (category_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_D11116CA4B89032C (post_id), INDEX IDX_D11116CA12469DE2 (category_id), PRIMARY KEY(category_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE category_post ADD CONSTRAINT FK_D11116CA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_post ADD CONSTRAINT FK_D11116CA4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
    }
}
