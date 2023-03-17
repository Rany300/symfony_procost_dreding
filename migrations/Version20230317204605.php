<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317204605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE work_unit (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, project_id INT NOT NULL, duration INT NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2578BD6B1B65292 (employe_id), INDEX IDX_2578BD6B166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_unit ADD CONSTRAINT FK_2578BD6B1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE work_unit ADD CONSTRAINT FK_2578BD6B166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_unit DROP FOREIGN KEY FK_2578BD6B1B65292');
        $this->addSql('ALTER TABLE work_unit DROP FOREIGN KEY FK_2578BD6B166D1F9C');
        $this->addSql('DROP TABLE work_unit');
    }
}
