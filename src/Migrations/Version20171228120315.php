<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20171228120315 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE citizen_project_category_skills DROP FOREIGN KEY FK_168C868A5585C142');
        $this->addSql('ALTER TABLE citizen_project_category_skills ADD CONSTRAINT FK_168C868A5585C142 FOREIGN KEY (skill_id) REFERENCES citizen_project_skills (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE citizen_project_category_skills DROP FOREIGN KEY FK_168C868A5585C142');
        $this->addSql('ALTER TABLE citizen_project_category_skills ADD CONSTRAINT FK_168C868A5585C142 FOREIGN KEY (skill_id) REFERENCES citizen_project_skills (id)');
    }
}
