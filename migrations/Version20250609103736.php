<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250609103736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE product_variant (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, size VARCHAR(10) NOT NULL, INDEX IDX_209AA41D4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock DROP FOREIGN KEY FK_4B3656604584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4B3656604584665A ON stock
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock CHANGE product_id variant_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock ADD CONSTRAINT FK_4B3656603B69A9AF FOREIGN KEY (variant_id) REFERENCES product_variant (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4B3656603B69A9AF ON stock (variant_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE stock DROP FOREIGN KEY FK_4B3656603B69A9AF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D4584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_variant
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4B3656603B69A9AF ON stock
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock CHANGE variant_id product_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock ADD CONSTRAINT FK_4B3656604584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4B3656604584665A ON stock (product_id)
        SQL);
    }
}
