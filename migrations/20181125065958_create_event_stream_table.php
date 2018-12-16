<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateEventStreamTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute(
            <<<'SQL'
    CREATE TABLE event_streams (
      no BIGSERIAL,
      real_stream_name VARCHAR(150) NOT NULL,
      stream_name CHAR(41) NOT NULL,
      metadata JSONB,
      category VARCHAR(150),
      PRIMARY KEY (no),
      UNIQUE (stream_name)
    );
SQL
        );
        $this->execute('CREATE INDEX on event_streams (category);');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DROP TABLE public.event_streams;');
    }
}
