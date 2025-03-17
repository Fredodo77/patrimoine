<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250308202644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amortissement (id INT AUTO_INCREMENT NOT NULL, num_credit_id INT NOT NULL, num_echeance DOUBLE PRECISION NOT NULL, date_echeance DATE NOT NULL, montant_amortissement NUMERIC(10, 2) NOT NULL, montant_interet NUMERIC(10, 2) NOT NULL, INDEX IDX_1335AE50BCD85B25 (num_credit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, montant NUMERIC(10, 2) NOT NULL, taux NUMERIC(5, 2) NOT NULL, assurance NUMERIC(5, 2) NOT NULL, premiere_echeance DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amortissement ADD CONSTRAINT FK_1335AE50BCD85B25 FOREIGN KEY (num_credit_id) REFERENCES credit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE amortissement DROP FOREIGN KEY FK_1335AE50BCD85B25');
        $this->addSql('DROP TABLE amortissement');
        $this->addSql('DROP TABLE credit');
    }
}
