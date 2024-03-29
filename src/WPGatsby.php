<?php

namespace TGHP\WPGatsbyTGHP;

use TGHP\WPGatsbyTGHP\WPGatsby\ActionMonitors;

class WPGatsby
{

    /**
     * @var ActionMonitors
     */
    public $actionMonitors;

    /**
     * @var string|null
     */
    protected $buildWebhookUrl;

    public function __construct()
    {
        if (!in_array('wp-gatsby/wp-gatsby.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            throw new \Exception('WP Gatsby is not installed or activated.');
        }

        $this->actionMonitors = new ActionMonitors();

        $wpGatsbyOptions = get_option('wpgatsby_settings');

        if (empty($wpGatsbyOptions)) {
            throw new \Exception('WP Gatsby options not found');
        }

        if (isset($wpGatsbyOptions['builds_api_webhook'])) {
            $this->buildWebhookUrl = (string) $wpGatsbyOptions['builds_api_webhook'];
        } else {
            throw new \Exception('WP Gatsby resource ID not found');
        }
    }

    /**
     * @return string|null
     */
    public function getBuildWebhookUrl()
    {
        return $this->buildWebhookUrl;
    }

}