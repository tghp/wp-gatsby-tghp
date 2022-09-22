<?php

namespace TGHP\WPGatsbyTGHP;

class Database
{

    public function __construct()
    {
        add_action('init', [$this, 'deltaTables']);
    }

    public function deltaTables()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;

        dbDelta("
            CREATE TABLE `{$wpdb->prefix}wpgatsbytghp_events` (
                `id` int NOT NULL AUTO_INCREMENT,
                `created_at` datetime NOT NULL,
                `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `duration` int DEFAULT NULL,
                `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
                PRIMARY KEY (`id`)
            )
            COLLATE {$wpdb->collate}
        ");
    }

    public function getEloquent(): \WeDevs\ORM\Eloquent\Database
    {
        return \WeDevs\ORM\Eloquent\Database::instance();
    }

}