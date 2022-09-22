<?php

namespace TGHP\WPGatsbyTGHP\WPGatsby\ActionMonitors;

use GraphQLRelay\Relay;

class PostMetaboxFieldSavedMonitor extends AbstractActionMonitor
{

    public function init()
    {
        add_action('rwmb_after_save_field', [$this, 'metaboxFieldSaved'], 10, 5);
    }

    public function metaboxFieldSaved($null, $field, $new, $old, $object_id)
    {
        if ($new !== $old) {
            $screen = get_current_screen();

            if ($screen->post_type) {
                $post = get_post($object_id);
                $postType = get_post_type_object($screen->post_type);

                if (property_exists($postType, 'graphql_single_name') && property_exists($postType, 'graphql_plural_name')) {
                    $this->log_action([
                        'action_type' => 'UPDATE',
                        'title' => $post->post_title,
                        'graphql_single_name' => $postType->graphql_single_name,
                        'graphql_plural_name' => $postType->graphql_plural_name,
                        'node_id' => $post->ID,
                        'relay_id' => Relay::toGlobalId('post', $post->ID),
                        'status' => $post->post_status,
                    ]);
                }
            }
        }
    }

}