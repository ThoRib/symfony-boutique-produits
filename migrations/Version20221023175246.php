<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221023175246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_fabricant (produit_id INT NOT NULL, fabricant_id INT NOT NULL, INDEX IDX_53D7FC85F347EFB (produit_id), INDEX IDX_53D7FC85CBAAAAB3 (fabricant_id), PRIMARY KEY(produit_id, fabricant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_fabricant ADD CONSTRAINT FK_53D7FC85F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_fabricant ADD CONSTRAINT FK_53D7FC85CBAAAAB3 FOREIGN KEY (fabricant_id) REFERENCES fabricant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_fabricant DROP FOREIGN KEY FK_53D7FC85F347EFB');
        $this->addSql('ALTER TABLE produit_fabricant DROP FOREIGN KEY FK_53D7FC85CBAAAAB3');
        $this->addSql('DROP TABLE produit_fabricant');
    }
}
