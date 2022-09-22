<?php

namespace TGHP\WPGatsbyTGHP\WPGatsby\ActionMonitors;

class SettingMetaboxFieldSavedMonitor extends AbstractActionMonitor
{

    public function init()
    {
        add_action('rwmb_after_save_field', [$this, 'metaboxFieldSaved'], 10, 5);
    }

    public function metaboxFieldSaved($null, $field, $new, $old, $object_id)
    {
        if ($new !== $old) {
            if (is_string($object_id) && isset($field['id'])) {
                $this->trigger_non_node_root_field_update([
                    'title' => __( 'Update Setting: ', 'WPGatsby' ) . ' ' . $field['id'],
                ]);
            }
        }
    }

}