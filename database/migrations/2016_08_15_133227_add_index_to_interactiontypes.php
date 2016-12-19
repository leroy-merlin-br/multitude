<?php

use MongoDB\Database;

/**
 * Base MongoDB migration.
 */
class AddIndexToInteractiontypes
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
        $db->interactionTypes->createIndex(['slug' => 1]);
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
        $db->interactionTypes->dropIndex('slug');
    }
}
