<?php

use MongoDB\Database;

/**
 * Base MongoDB migration
 */
class AddIndexToSegments
{
    /**
     * Run the migrations
     *
     * @param  Database $db MongoDB Database.
     *
     * @return void
     */
    public function up(Database $db)
    {
        $db->collectionName->createIndex(['slug' => 1]);
    }

    /**
     * Reverse the migrations
     *
     * @param  Database $db MongoDB Database.
     *
     * @return void
     */
    public function down(Database $db)
    {
        $db->collectionName->dropIndex('slug');
    }
}
