<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221115185604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "restaurant_order" (id UUID NOT NULL, customer_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "restaurant_order".customer_id IS \'(DC2Type:order_customer_uuid_id)\'');
        $this->addSql('COMMENT ON COLUMN "restaurant_order".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "restaurant_order_item" (id UUID NOT NULL, order_id UUID NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F7D03F388D9F6D38 ON "restaurant_order_item" (order_id)');
        $this->addSql('COMMENT ON COLUMN "restaurant_order_item".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "restaurant_order_item" ADD CONSTRAINT FK_F7D03F388D9F6D38 FOREIGN KEY (order_id) REFERENCES "restaurant_order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "restaurant_order_item" DROP CONSTRAINT FK_F7D03F388D9F6D38');
        $this->addSql('DROP TABLE "restaurant_order"');
        $this->addSql('DROP TABLE "restaurant_order_item"');
    }
}
