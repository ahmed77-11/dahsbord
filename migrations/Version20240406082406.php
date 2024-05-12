<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406082406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie ADD cat_img VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE nom_categ nom_cateq VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image_produit CHANGE produit_id produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marque ADD marque_img VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE produit ADD user_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE marque_id marque_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29A5EC27A76ED395 ON produit (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP cat_img, DROP created_at, CHANGE nom_cateq nom_categ VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image_produit CHANGE produit_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE marque DROP marque_img, DROP created_at');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A76ED395');
        $this->addSql('DROP INDEX UNIQ_29A5EC27A76ED395 ON produit');
        $this->addSql('ALTER TABLE produit DROP user_id, DROP created_at, CHANGE marque_id marque_id INT NOT NULL');
    }
}
