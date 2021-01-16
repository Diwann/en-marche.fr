<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190829161721 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX donator_unique_matching ON donators (email_address, first_name, last_name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX donator_unique_matching ON donators');
    }
}
