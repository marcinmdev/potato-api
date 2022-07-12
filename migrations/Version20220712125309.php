<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220712125309 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (id SERIAL NOT NULL, name TEXT NOT NULL, price INT NOT NULL, weight INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE meal (id SERIAL NOT NULL, name TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE meal_ingredient (meal_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(meal_id, ingredient_id))');
        $this->addSql('CREATE INDEX IDX_FCC3CEFA639666D6 ON meal_ingredient (meal_id)');
        $this->addSql('CREATE INDEX IDX_FCC3CEFA933FE08C ON meal_ingredient (ingredient_id)');
        $this->addSql('CREATE TABLE user_account (id SERIAL NOT NULL, api_token TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_253B48AE7BA2F5EB ON user_account (api_token)');
        $this->addSql('ALTER TABLE meal_ingredient ADD CONSTRAINT FK_FCC3CEFA639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meal_ingredient ADD CONSTRAINT FK_FCC3CEFA933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal_ingredient DROP CONSTRAINT FK_FCC3CEFA933FE08C');
        $this->addSql('ALTER TABLE meal_ingredient DROP CONSTRAINT FK_FCC3CEFA639666D6');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE meal_ingredient');
        $this->addSql('DROP TABLE user_account');
    }
}
