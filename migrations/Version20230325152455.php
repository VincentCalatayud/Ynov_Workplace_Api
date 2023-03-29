<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230325152455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation ADD owner_id INT NOT NULL, ADD target_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E97E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E96C066AFE FOREIGN KEY (target_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E97E3C61F9 ON conversation (owner_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E96C066AFE ON conversation (target_user_id)');
        $this->addSql('ALTER TABLE private_message ADD owner_id INT NOT NULL, ADD related_conversation_id INT NOT NULL');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9B84C456BF FOREIGN KEY (related_conversation_id) REFERENCES conversation (id)');
        $this->addSql('CREATE INDEX IDX_4744FC9B7E3C61F9 ON private_message (owner_id)');
        $this->addSql('CREATE INDEX IDX_4744FC9B84C456BF ON private_message (related_conversation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E97E3C61F9');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E96C066AFE');
        $this->addSql('DROP INDEX IDX_8A8E26E97E3C61F9 ON conversation');
        $this->addSql('DROP INDEX IDX_8A8E26E96C066AFE ON conversation');
        $this->addSql('ALTER TABLE conversation DROP owner_id, DROP target_user_id');
        $this->addSql('ALTER TABLE private_message DROP FOREIGN KEY FK_4744FC9B7E3C61F9');
        $this->addSql('ALTER TABLE private_message DROP FOREIGN KEY FK_4744FC9B84C456BF');
        $this->addSql('DROP INDEX IDX_4744FC9B7E3C61F9 ON private_message');
        $this->addSql('DROP INDEX IDX_4744FC9B84C456BF ON private_message');
        $this->addSql('ALTER TABLE private_message DROP owner_id, DROP related_conversation_id');
    }
}
