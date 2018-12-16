<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateSnapshotsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(
<<<'SQL'
    CREATE TABLE snapshots (
      aggregate_id VARCHAR(150) NOT NULL,
      aggregate_type VARCHAR(150) NOT NULL,
      last_version INT NOT NULL,
      created_at CHAR(26) NOT NULL,
      aggregate_root BYTEA,
      PRIMARY KEY (aggregate_id)
    );
SQL
        );
    }

    public function down(): void
    {
        $this->execute('DROP TABLE public.snapshots;');
    }
}
