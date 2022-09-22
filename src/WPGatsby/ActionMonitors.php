<?php

namespace TGHP\WPGatsbyTGHP\WPGatsby;

class ActionMonitors
{

    public function __construct()
    {
        add_filter('gatsby_action_monitors', function (array $monitors, \WPGatsby\ActionMonitor\ActionMonitor $actionMonitor) {
            foreach ($this->_getActionMonitors($actionMonitor) as $actionMonitor) {
                $monitors[get_class($actionMonitor)] = $actionMonitor;
            }

            return $monitors;
        }, 10, 2);
    }

    protected function _getActionMonitors(\WPGatsby\ActionMonitor\ActionMonitor $actionMonitor)
    {
        return [
            new ActionMonitors\TaxonomyMetaboxFieldSavedMonitor($actionMonitor),
            new ActionMonitors\SettingMetaboxFieldSavedMonitor($actionMonitor),
            new ActionMonitors\PostMetaboxFieldSavedMonitor($actionMonitor),
            new ActionMonitors\PostAssignedToTermMonitor($actionMonitor),
        ];
    }

}