<?php

namespace TGHP\WPGatsbyTGHP;

class AdminPages
{

    public function __construct()
    {
        // add_action('admin_menu', function () {
        //     add_management_page(
        //         'WP Gatsby TGHP Events',
        //         'WP Gatsby TGHP Events',
        //         'manage_options',
        //         'wpgatsbytghp-events',
        //         [$this, 'eventsPage']
        //     );
        // });
    }

    public function eventsPage()
    {
        include WP_GATSBY_TGHP_PLUGIN_PATH . '/templates/pages/events.php';
    }

}