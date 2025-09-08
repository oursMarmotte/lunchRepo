<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250323175817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture ADD entre_chef_id INT DEFAULT NULL, ADD dessert_chef_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8973185603 FOREIGN KEY (entre_chef_id) REFERENCES entre_du_chef (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8939150A8E FOREIGN KEY (dessert_chef_id) REFERENCES dessert_du_chef (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F8973185603 ON picture (entre_chef_id)');
        $this->addSql('CREATE INDEX IDX_16DB4F8939150A8E ON picture (dessert_chef_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8973185603');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8939150A8E');
        $this->addSql('DROP INDEX IDX_16DB4F8973185603 ON picture');
        $this->addSql('DROP INDEX IDX_16DB4F8939150A8E ON picture');
        $this->addSql('ALTER TABLE picture DROP entre_chef_id, DROP dessert_chef_id');
    }
}
