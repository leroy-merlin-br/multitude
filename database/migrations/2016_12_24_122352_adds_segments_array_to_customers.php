<?php

use Leadgen\Customer\Customer;
use MongoDB\Database;

/**
 * Base MongoDB migration
 */
class AddsSegmentsArrayToCustomers
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
        $db->customers->updateMany(
            [], // update all
            [
                '$set' => [
                    'segments' => [], // Force to be an array
                ],
            ]
        );
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
        // does nothing
    }
}
