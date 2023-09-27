<?php

namespace TGHP\WPGatsbyTGHP\Actions;

use Exception;

class TriggerBuild implements ActionInterface
{

    const ACTION_CODE = 'wpgatsbytghp-trigger-build';

    const LOCAL_EVENT_TRIGGER_BUILD = 'TRIGGER_BUILD';

    public function getActionCode(): string
    {
        return self::ACTION_CODE;
    }

    /**
     * Execute action, when logged in, likely came from the admin area
     *
     * @return string
     * @throws Exception
     */
    public function executePriv(): string
    {
        if (!wp_verify_nonce($_REQUEST['_wpnonce'], self::ACTION_CODE)) {
            throw new Exception('Invalid nonce');
        }

        $this->_triggerBuild();

        if (wp_get_referer()) {
            wp_redirect(wp_get_referer());
            exit;
        } else {
            return json_encode(['success' => true]);
        }
    }

    /**
     * Execute non-privleged, where a matching resource ID must be provided to prove the request is valid
     *
     * @return string
     * @throws Exception
     */
    public function executeNoPriv(): string
    {
        $this->_triggerBuild();

        return json_encode(['success' => true]);
    }

    /**
     * Trigger a build, called from both priv and nopriv methods
     *
     * @return void
     */
    protected function _triggerBuild(): void
    {
        $curl = curl_init(WPGatsbyTGHP()->wpGatsby->getBuildWebhookUrl());
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        $response = curl_exec($curl);
        curl_close($curl);

        $db = WPGatsbyTGHP()->database->getEloquent();

        $db->table('wpgatsbytghp_events')->insert([
            'created_at' => date('Y-m-d H:i:s'),
            'event' => 'TRIGGER_BUILD',
            'payload' => json_encode([
                'user' => get_current_user_id(),
            ]),
        ]);
}

}