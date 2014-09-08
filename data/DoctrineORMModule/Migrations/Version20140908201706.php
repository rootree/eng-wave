<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140908201706 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $sql = <<<SQL

SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema)
    {
        $sql = <<<SQL

SQL;
        $this->addSql($sql);
    }
}
