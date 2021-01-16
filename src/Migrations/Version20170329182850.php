<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20170329182850 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE procuration_proxies ADD invite_source_first_name VARCHAR(100) DEFAULT NULL, CHANGE reliability reliability SMALLINT NOT NULL, CHANGE disabled disabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE procuration_proxies DROP invite_source_first_name, CHANGE reliability reliability SMALLINT DEFAULT 0, CHANGE disabled disabled TINYINT(1) DEFAULT \'0\'');
    }
}
