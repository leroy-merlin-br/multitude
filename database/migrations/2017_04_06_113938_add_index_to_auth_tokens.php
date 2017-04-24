<?php

use MongoDB\Database;

/**
 * Base MongoDB migration
 */
class AddIndexToAuthTokens
{
    /**
     * Run the migrations
     *
     * @param  Database $db MongoDB Database
     *
     * @return void
     */
    public function up(Database $db)
    {
        $db->auth_tokens->createIndex(['secret' => 1]);
    }

    /**
     * Reverse the migrations
     *
     * @param  Database $db MongoDB Database
     *
     * @return void
     */
    public function down(Database $db)
    {
        $db->auth_tokens->dropIndex(['secret']);
    }
}
