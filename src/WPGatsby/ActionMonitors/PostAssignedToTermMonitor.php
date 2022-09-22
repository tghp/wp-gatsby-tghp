<?php

namespace TGHP\WPGatsbyTGHP\WPGatsby\ActionMonitors;

use GraphQLRelay\Relay;

class PostAssignedToTermMonitor extends AbstractActionMonitor
{

    public function init()
    {
        add_action('set_object_terms', [$this, 'setObjectTerms'], 10, 6);
    }

    public function setObjectTerms(int $object_id, array $terms, array $tt_ids, string $taxonomy, bool $append, array $old_tt_ids)
    {
        $tt_ids = array_map(function ($id) {
            return (int) $id;
        }, $tt_ids);
        sort($tt_ids);

        $old_tt_ids = array_map(function ($id) {
            return (int) $id;
        }, $old_tt_ids);
        sort($old_tt_ids);

        $newTerms = array_diff($tt_ids, $old_tt_ids);

        if (empty($newTerms)) {
            return;
        }

        $post = get_post($object_id);

        if ($post->post_type === 'action_monitor') {
            return;
        }

        $postTypeObject = get_post_type_object($post->post_type);
        $taxonomyObject = get_taxonomy($taxonomy);

        if (property_exists($postTypeObject, 'graphql_single_name') && property_exists($postTypeObject, 'graphql_plural_name') &&
                property_exists($taxonomyObject, 'graphql_single_name') && property_exists($taxonomyObject, 'graphql_plural_name')) {

            $this->log_action([
                'action_type' => 'UPDATE',
                'title' => $post->post_title,
                'graphql_single_name' => $postTypeObject->graphql_single_name,
                'graphql_plural_name' => $postTypeObject->graphql_plural_name,
                'node_id' => $post->ID,
                'relay_id' => Relay::toGlobalId('post', $post->ID),
                'status' => $post->post_status,
            ]);

            foreach ($newTerms as $termId) {
                $term = get_term($termId, $taxonomyObject->name);

                $this->log_action([
                    'action_type' => 'UPDATE',
                    'title' => $term->name,
                    'graphql_single_name' => $taxonomyObject->graphql_single_name,
                    'graphql_plural_name' => $taxonomyObject->graphql_plural_name,
                    'status' => 'publish',
                    'node_id' => $term->term_id,
                    'relay_id' => Relay::toGlobalId('term', $term->term_id),
                ]);
            }
        }
    }

}