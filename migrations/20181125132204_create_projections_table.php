<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateProjectionsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(
<<<'SQL'
    CREATE TABLE projections (
      no BIGSERIAL,
      name VARCHAR(150) NOT NULL,
      position JSONB,
      state JSONB,
      status VARCHAR(28) NOT NULL,
      locked_until CHAR(26),
      PRIMARY KEY (no),
      UNIQUE (name)
    );
SQL
        );
    }

    public function down()
    {
        $this->execute('DROP TABLE public.projections');
    }
}
