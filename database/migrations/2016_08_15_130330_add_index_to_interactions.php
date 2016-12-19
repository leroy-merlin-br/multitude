<?php

use MongoDB\Database;

/**
 * Base MongoDB migration.
 */
class AddIndexToInteractions
{
    /**
     * Run the migrations.
     *
     * @param Database $db MongoDB Database
     *
     * @return void
     */
    public function up(Database $db)
    {
        $db->interactions->createIndex(['acknowledged' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @param Database $db MongoDB Database
     *
     * @return void
     */
    public function down(Database $db)
    {
        $db->interactions->dropIndex('acknowledged');
    }
}
