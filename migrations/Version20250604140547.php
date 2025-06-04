<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604140547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE mouvement_stock (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, location_id INT NOT NULL, user_id INT NOT NULL, quantity INT NOT NULL, type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', commentaire LONGTEXT DEFAULT NULL, INDEX IDX_61E2C8EB4584665A (product_id), INDEX IDX_61E2C8EB64D218E (location_id), INDEX IDX_61E2C8EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, brand_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, price INT NOT NULL, reference VARCHAR(255) NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04AD44F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_location (product_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_952EE35C4584665A (product_id), INDEX IDX_952EE35C64D218E (location_id), PRIMARY KEY(product_id, location_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, location_id INT NOT NULL, quantity INT NOT NULL, updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_4B3656604584665A (product_id), INDEX IDX_4B36566064D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EB4584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EB64D218E FOREIGN KEY (location_id) REFERENCES location (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_location ADD CONSTRAINT FK_952EE35C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_location ADD CONSTRAINT FK_952EE35C64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock ADD CONSTRAINT FK_4B3656604584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock ADD CONSTRAINT FK_4B36566064D218E FOREIGN KEY (location_id) REFERENCES location (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EB4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EB64D218E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EBA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_location DROP FOREIGN KEY FK_952EE35C4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_location DROP FOREIGN KEY FK_952EE35C64D218E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock DROP FOREIGN KEY FK_4B3656604584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock DROP FOREIGN KEY FK_4B36566064D218E
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE brand
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE mouvement_stock
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE stock
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
