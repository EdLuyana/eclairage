<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604124031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE mouvement_stock (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, emplacement_id INT NOT NULL, user_id INT NOT NULL, quantity INT NOT NULL, type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_61E2C8EB4584665A (product_id), INDEX IDX_61E2C8EBC4598A51 (emplacement_id), INDEX IDX_61E2C8EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EB4584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EBC4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EB4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EBC4598A51
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EBA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE mouvement_stock
        SQL);
    }
}
