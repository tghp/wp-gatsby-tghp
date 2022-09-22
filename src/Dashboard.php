<?php

namespace TGHP\WPGatsbyTGHP;

class Dashboard
{

    public function __construct()
    {
        add_action('wp_dashboard_setup', function () {
            global $wp_meta_boxes;

            wp_add_dashboard_widget(
                'wp_gatsby_tghp',
                'WP Gatsby TGHP',
                [$this, 'renderWidget'],
                null,
                null,
                'normal',
                'high'
            );
        });
    }

    public function renderWidget()
    {
        include WP_GATSBY_TGHP_PLUGIN_PATH . '/templates/dashboard/widget.php';
    }

}