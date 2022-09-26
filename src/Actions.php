<?php

namespace TGHP\WPGatsbyTGHP;

use TGHP\WPGatsbyTGHP\Actions\ActionInterface;

class Actions
{

    public function __construct()
    {
        foreach ($this->_getActions() as $action) {
            if ($action instanceof ActionInterface) {
                add_action('rest_api_init', function () use ($action) {
                    register_rest_route('wpgatsbytghp/v1', $action->getActionCode(), [
                        'methods' => 'POST',
                        'callback' => function ($request) use ($action) {
                            try {
                                return rest_ensure_response(
                                    call_user_func([$action, 'executeNoPriv'])
                                );
                            } catch (\Exception $e) {
                                return new \WP_Error('wpgatsbytghp_error', $e->getMessage(), [ 'status' => 503 ]);
                            }
                        },
                    ]);
                });

                add_action('admin_post_' . $action->getActionCode(), function () use ($action) {
                    try {
                        $return = call_user_func([$action, 'executePriv']);
                        status_header(200);
                        exit($return);
                    } catch (\Exception $e) {
                        status_header(503);
                        exit(json_encode([ 'success' => false, 'message' => $e->getMessage() ]));
                    }
                });

                add_action('admin_post_nopriv_' . $action->getActionCode(), function () use ($action) {
                    try {
                        $return = call_user_func([$action, 'executeNoPriv']);
                        status_header(200);
                        exit($return);
                    } catch (\Exception $e) {
                        status_header(503);
                        exit(json_encode([ 'success' => false, 'message' => $e->getMessage() ]));
                    }
                });
            }
        }
    }

    protected function _getActions()
    {
        return [
            new Actions\TriggerBuild(),
            new Actions\ReceiveWebhook(),
        ];
    }

}