<?php

namespace TGHP\WPGatsbyTGHP\WPGatsby\ActionMonitors;

use GraphQLRelay\Relay;

class SCPOrderChangeMonitor extends AbstractActionMonitor
{

    public function init()
    {
        add_action('scp_update_menu_order', [$this, 'menuOrderUpdated'], 10);
    }

    public function menuOrderUpdated()
    {
        if (isset($_POST['order'])) {
            $posts = wp_parse_args($_POST['order']);

            if (!empty($posts) && is_array($posts) && !empty($posts['post'])) {
                $post = get_post($posts['post'][0]);
                $postType = get_post_type_object($post->post_type);

                if (property_exists($postType, 'graphql_single_name') && property_exists($postType, 'graphql_plural_name')) {
                    global $wpdb;

                    // Get IDS and titles efficiently
                    $affectedPostResults = $wpdb->get_results(
                        sprintf("SELECT ID, post_title, menu_order, post_status FROM {$wpdb->posts} WHERE ID IN (%s)", implode(',', $posts['post'])),
                        ARRAY_A
                    );

                    foreach ($affectedPostResults as $affectedPost) {
                        $this->log_action([
                            'action_type' => 'UPDATE',
                            'title' => $affectedPost['post_title'],
                            'graphql_single_name' => $postType->graphql_single_name,
                            'graphql_plural_name' => $postType->graphql_plural_name,
                            'node_id' => intval($affectedPost['ID']),
                            'relay_id' => Relay::toGlobalId('post', intval($affectedPost['ID'])),
                            'status' => $affectedPost['post_status'],
                        ]);
                    }
                }
            }
        }
    }

}