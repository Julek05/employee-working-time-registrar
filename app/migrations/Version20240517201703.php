<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240517201703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE working_time (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', employee_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', start DATETIME NOT NULL, end DATETIME NOT NULL, start_day DATE NOT NULL, INDEX IDX_31EE2ABF8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE working_time ADD CONSTRAINT FK_31EE2ABF8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE working_time DROP FOREIGN KEY FK_31EE2ABF8C03F15C');
        $this->addSql('DROP TABLE working_time');
    }
}
