<?php

namespace TGHP\WPGatsbyTGHP\Actions;

use Exception;

class ReceiveWebhook implements ActionInterface
{

    const ACTION_CODE = 'wpgatsbytghp-receive-webhook';

    const GATSBY_WEBHOOK_EVENT_BUILD_SUCCEEDED = 'BUILD_SUCCEEDED';
    const GATSBY_WEBHOOK_EVENT_BUILD_FAILED = 'BUILD_FAILED';
    const GATSBY_WEBHOOK_EVENT_DEPLOY_SUCCEEDED = 'DEPLOY_SUCCEEDED';
    const GATSBY_WEBHOOK_EVENT_DEPLOY_FAILED = 'DEPLOY_FAILED';

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
        throw new Exception('Not implemented');
    }

    /**
     * Execute non-privleged, where a matching resource ID must be provided to prove the request is valid
     *
     * @return string
     * @throws Exception
     */
    public function executeNoPriv(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['event'])) {
            throw new Exception('No event found');
        }

        $db = WPGatsbyTGHP()->database->getEloquent();

        $db->table('wpgatsbytghp_events')->insert([
            'created_at' => date('Y-m-d H:i:s'),
            'event' => $data['event'],
            'duration' => $data['duration'] && intval($data['duration']) > 0 ? $data['duration'] : '',
            'payload' => json_encode($data),
        ]);

        return json_encode(['success' => true]);
    }

}