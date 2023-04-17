<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408164658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4FAF8F53');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9D86650F');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX idx_9474526c4faf8f53 ON comment');
        $this->addSql('CREATE INDEX IDX_9474526C1E27F6BF ON comment (question_id)');
        $this->addSql('DROP INDEX idx_9474526c9d86650f ON comment');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4FAF8F53 FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9D86650F');
        $this->addSql('ALTER TABLE question CHANGE user_id user_id INT NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE question question VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX idx_b6f7494e9d86650f ON question');
        $this->addSql('CREATE INDEX IDX_B6F7494EA76ED395 ON question (user_id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E9D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD image VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C1E27F6BF');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT NOT NULL');
        $this->addSql('DROP INDEX idx_9474526ca76ed395 ON comment');
        $this->addSql('CREATE INDEX IDX_9474526C9D86650F ON comment (user_id)');
        $this->addSql('DROP INDEX idx_9474526c1e27f6bf ON comment');
        $this->addSql('CREATE INDEX IDX_9474526C4FAF8F53 ON comment (question_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EA76ED395');
        $this->addSql('ALTER TABLE question CHANGE user_id user_id INT DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE question question TEXT DEFAULT NULL');
        $this->addSql('DROP INDEX idx_b6f7494ea76ed395 ON question');
        $this->addSql('CREATE INDEX IDX_B6F7494E9D86650F ON question (user_id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user DROP image, CHANGE password password VARCHAR(200) DEFAULT NULL');
    }
}
