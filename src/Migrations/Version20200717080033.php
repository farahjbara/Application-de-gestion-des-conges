<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200717080033 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, cin VARCHAR(8) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, fonction VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, solde_annuel INT DEFAULT NULL, nbr_jrs_pris INT DEFAULT NULL, nbr_jrs_restant INT DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date_demande DATE NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, type_conge VARCHAR(255) NOT NULL, fichier_joint LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', etat VARCHAR(30) NOT NULL, INDEX IDX_2694D7A5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, nom_projet VARCHAR(255) NOT NULL, date_debut_projet DATE NOT NULL, date_limite DATE NOT NULL, etat_projet VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, date_permission DATE NOT NULL, etat_permission VARCHAR(255) NOT NULL, approbation_chef TINYINT(1) NOT NULL, INDEX IDX_E04992AAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conge (id INT AUTO_INCREMENT NOT NULL, id_conge VARCHAR(255) NOT NULL, solde_annuel VARCHAR(255) NOT NULL, nbr_jrs_pris INT NOT NULL, nbr_jrs_restant INT NOT NULL, type VARCHAR(255) NOT NULL, etat_conge VARCHAR(255) NOT NULL, approbation_chef TINYINT(1) NOT NULL, approbation_rh TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5A76ED395');
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AAA76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE conge');
    }
}
