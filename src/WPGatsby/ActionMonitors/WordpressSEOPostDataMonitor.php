<?php

namespace TGHP\WPGatsbyTGHP\WPGatsby\ActionMonitors;

use GraphQLRelay\Relay;
use Illuminate\Support\Arr;
use WP_Post;

class WordpressSEOPostDataMonitor extends AbstractActionMonitor
{

    protected $postID;
    protected $postMeta;

    public function init()
    {
        add_action('wpseo_save_compare_data', [$this, 'storePostMeta'], 10, 1);
        add_action('wpseo_saved_postdata', [$this, 'comparePostMeta'], 10);
    }

    /**
     * @param WP_Post $post
     * @return void
     */
    public function storePostMeta($post)
    {
        if ($post instanceof WP_Post) {
            $this->postID = $post->ID;
            $this->postMeta = Arr::sortRecursive(get_post_meta($post->ID));
        }
    }

    public function comparePostMeta()
    {
        if ($this->postID && $this->postMeta) {
            $postMetaNow = Arr::sortRecursive(get_post_meta($this->postID));
            $metaDiff = array_diff(
                Arr::dot($this->postMeta),
                Arr::dot($postMetaNow)
            );

            if (!empty($metaDiff)) {
                $post = get_post($this->postID);
                $postType = get_post_type_object($post->post_type);

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

            $this->postID = null;
            $this->postMeta = null;
        }
    }

}