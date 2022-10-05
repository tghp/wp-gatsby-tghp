<?php

use TGHP\WPGatsbyTGHP\Actions\ReceiveWebhook;

$timeAgo = new Westsworld\TimeAgo();
$db = WPGatsbyTGHP()->database->getEloquent();

$totalEvents = $db->table('wpgatsbytghp_events')
    ->count();

$recentCount = 20;

if (isset($_GET['older'])) {
    $events = $db->table('wpgatsbytghp_events')
        ->orderBy('created_at', 'desc')
        ->limit($totalEvents)
        ->offset($recentCount)
        ->get();
} else {
    $events = $db->table('wpgatsbytghp_events')
        ->orderBy('created_at', 'desc')
        ->limit($recentCount)
        ->get();
}

$gatsbyIncomingEvents = [
    ReceiveWebhook::GATSBY_WEBHOOK_EVENT_BUILD_SUCCEEDED,
    ReceiveWebhook::GATSBY_WEBHOOK_EVENT_BUILD_FAILED,
    ReceiveWebhook::GATSBY_WEBHOOK_EVENT_DEPLOY_SUCCEEDED,
    ReceiveWebhook::GATSBY_WEBHOOK_EVENT_DEPLOY_FAILED,
];
?>
<div class="wrap">
    <div class="tablenav top">
        <i>Webhook URL: <?= site_url('wp-json/wpgatsbytghp/v1/wpgatsbytghp-receive-webhook') ?></i>
        <div class="tablenav-pages one-page"><span class="displaying-num"><?= $totalEvents ?> items</span></div>
        <br class="clear">
    </div>

    <table class="wp-list-table widefat fixed striped table-view-list pages">
        <tbody id="the-list" class="ui-sortable">
            <?php foreach ($events->all() as $event):
                if ($event->payload) {
                    $payload = json_decode($event->payload, true);
                } else {
                    $payload = [];
                }
                ?>
                <tr class="level-0">
                    <td class="event column-event column-primary" data-colname="event">
                        <?= ucwords(strtolower(str_replace('_', ' ', $event->event))) ?><br>
                    </td>
                    <td class="date column-duration" data-colname="Duration">
                        <?php if ($event->duration): ?>
                            Took <?= $event->duration ?>s
                        <?php endif ?>
                    </td>
                    <td class="author column-author" data-colname="Triggered by User">
                        <?php if (in_array($event->event, $gatsbyIncomingEvents)): ?>
                            <span>In Gatsby Cloud</span>
                        <?php elseif (isset($payload['user'])):
                            $user = get_userdata($payload['user']);
                            ?>
                            <?php if ($payload['user'] === 0): ?>
                                <span>Via Webhook</span>
                            <?php elseif ($user): ?>
                                <span>Triggered By </span>
                                <a href="<?= admin_url("edit.php?post_type=page&amp;author={$user->ID}") ?>">
                                    <?= $user->user_nicename ?>
                                </a>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <td class="date column-date" data-colname="Date">
                        <strong><?= $timeAgo->inWords(new DateTime($event->created_at)) ?></strong><br />
                        <sub><?= $event->created_at ?></sub>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <p class="submit">
        <?php if (!isset($_GET['older']) && $totalEvents > $recentCount): ?>
            <a href="<?= admin_url('tools.php?page=wpgatsbytghp-events&older=1') ?>" class="button button-primary">
                <?= __('View Older') ?>
            </a>
        <?php endif ?>
    </p>
</div>