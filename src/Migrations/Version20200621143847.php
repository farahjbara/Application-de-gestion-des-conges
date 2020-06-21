<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200621143847 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demande CHANGE fichier_joint fichier_joint LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\'');
        $this->addSql('ALTER TABLE user ADD old_password VARCHAR(255) DEFAULT NULL, ADD new_password VARCHAR(255) DEFAULT NULL, ADD new_confirm_password VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE solde_annuel solde_annuel INT DEFAULT NULL, CHANGE nbr_jrs_pris nbr_jrs_pris INT DEFAULT NULL, CHANGE nbr_jrs_restant nbr_jrs_restant INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demande CHANGE fichier_joint fichier_joint LONGTEXT CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\'');
        $this->addSql('ALTER TABLE user DROP old_password, DROP new_password, DROP new_confirm_password, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE solde_annuel solde_annuel INT DEFAULT NULL, CHANGE nbr_jrs_pris nbr_jrs_pris INT DEFAULT NULL, CHANGE nbr_jrs_restant nbr_jrs_restant INT DEFAULT NULL');
    }
}
