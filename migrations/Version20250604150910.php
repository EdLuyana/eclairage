<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604150910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE stock_movement (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, location_id INT NOT NULL, user_id INT NOT NULL, quantity INT NOT NULL, type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', commentaire LONGTEXT DEFAULT NULL, INDEX IDX_BB1BC1B54584665A (product_id), INDEX IDX_BB1BC1B564D218E (location_id), INDEX IDX_BB1BC1B5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B54584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B564D218E FOREIGN KEY (location_id) REFERENCES location (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
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
            DROP TABLE mouvement_stock
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE mouvement_stock (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, location_id INT NOT NULL, user_id INT NOT NULL, quantity INT NOT NULL, type VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', commentaire LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_61E2C8EB4584665A (product_id), INDEX IDX_61E2C8EB64D218E (location_id), INDEX IDX_61E2C8EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EB4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EB64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_movement DROP FOREIGN KEY FK_BB1BC1B54584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_movement DROP FOREIGN KEY FK_BB1BC1B564D218E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_movement DROP FOREIGN KEY FK_BB1BC1B5A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE stock_movement
        SQL);
    }
}
