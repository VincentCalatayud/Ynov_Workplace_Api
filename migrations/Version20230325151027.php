<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230325151027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, target_user_id INT NOT NULL, INDEX IDX_8A8E26E97E3C61F9 (owner_id), INDEX IDX_8A8E26E96C066AFE (target_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE private_message (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, related_conversation_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4744FC9B7E3C61F9 (owner_id), INDEX IDX_4744FC9B84C456BF (related_conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E97E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E96C066AFE FOREIGN KEY (target_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9B84C456BF FOREIGN KEY (related_conversation_id) REFERENCES conversation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E97E3C61F9');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E96C066AFE');
        $this->addSql('ALTER TABLE private_message DROP FOREIGN KEY FK_4744FC9B7E3C61F9');
        $this->addSql('ALTER TABLE private_message DROP FOREIGN KEY FK_4744FC9B84C456BF');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE private_message');
    }
}
