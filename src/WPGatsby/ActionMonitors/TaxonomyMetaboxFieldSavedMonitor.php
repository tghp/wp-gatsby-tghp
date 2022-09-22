<?php

namespace TGHP\WPGatsbyTGHP\WPGatsby\ActionMonitors;

use GraphQLRelay\Relay;

class TaxonomyMetaboxFieldSavedMonitor extends AbstractActionMonitor
{

    public function init()
    {
        add_action('rwmb_after_save_field', [$this, 'metaboxFieldSaved'], 10, 5);
    }

    public function metaboxFieldSaved($null, $field, $new, $old, $object_id)
    {
        if ($new !== $old) {
            $screen = get_current_screen();

            if ($screen->taxonomy) {
                $taxonomy = get_taxonomy($screen->taxonomy);

                if (property_exists($taxonomy, 'graphql_single_name') && property_exists($taxonomy, 'graphql_plural_name')) {
                    $term = get_term($object_id, $screen->taxonomy);

                    $this->log_action([
                        'action_type' => 'UPDATE',
                        'title' => $term->name,
                        'graphql_single_name' => $taxonomy->graphql_single_name,
                        'graphql_plural_name' => $taxonomy->graphql_plural_name,
                        'status' => 'publish',
                        'node_id' => $term->term_id,
                        'relay_id' => Relay::toGlobalId('term', $term->term_id),
                    ]);
                }
            }
        }
    }

}