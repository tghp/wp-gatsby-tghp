<?php

use TGHP\WPGatsbyTGHP\Actions\ReceiveWebhook;
use TGHP\WPGatsbyTGHP\Actions\TriggerBuild;

$timeAgo = new Westsworld\TimeAgo();
$db = WPGatsbyTGHP()->database->getEloquent();

$lastTrigger = $db->table('wpgatsbytghp_events')
    ->select('created_at')
    ->where('event', TriggerBuild::LOCAL_EVENT_TRIGGER_BUILD)
    ->orderBy('created_at', 'desc')
    ->limit(1)
    ->get();

$lastSuccessfulBuild = $db->table('wpgatsbytghp_events')
    ->select('created_at')
    ->where('event', ReceiveWebhook::GATSBY_WEBHOOK_EVENT_BUILD_SUCCEEDED)
    ->orderBy('created_at', 'desc')
    ->limit(1)
    ->get();

$lastFailedBuild = $db->table('wpgatsbytghp_events')
    ->select('created_at')
    ->where('event', ReceiveWebhook::GATSBY_WEBHOOK_EVENT_BUILD_FAILED)
    ->orderBy('created_at', 'desc')
    ->limit(1)
    ->get();
?>
<div id="dashboard_right_now">
    <ul>
        <li>
            <strong><?= __('Last Successful Build') ?></strong><br>
            <span class="search-engines-info">
                <?php if ($lastSuccessfulBuild->count()): ?>
                    <strong class="data"><?= $timeAgo->inWords(new DateTime($lastSuccessfulBuild->first()->created_at)) ?></strong><br>
                    <sub class="data"><?= $lastSuccessfulBuild->first()->created_at ?></sub>
                <?php else: ?>
                    <span class="no-data"><?= __('Unknown') ?></span>
                <?php endif ?>
            </span>
        </li>
        <li>
            <strong><?= __('Last Failed Build') ?></strong><br>
            <span class="search-engines-info">
                <?php if ($lastFailedBuild->count()): ?>
                    <strong class="data"><?= $timeAgo->inWords(new DateTime($lastFailedBuild->first()->created_at)) ?></strong><br>
                    <sub class="data"><?= $lastFailedBuild->first()->created_at ?></sub>
                <?php else: ?>
                    <span class="no-data"><?= __('Unknown') ?></span>
                <?php endif ?>
            </span>
        </li>
        <li style="width: 100%;">
            <hr>
            <br>
            <strong><?= __('Last Manual Build Trigger') ?></strong><br>
            <span class="search-engines-info">
                <?php if ($lastTrigger->count()): ?>
                    <strong class="data"><?= $timeAgo->inWords(new DateTime($lastTrigger->first()->created_at)) ?></strong><br>
                    <sub class="data"><?= $lastTrigger->first()->created_at ?></sub>
                <?php else: ?>
                    <span class="no-data"><?= __('N/A') ?></span>
                <?php endif ?>
            </span>
        </li>
    </ul>
</div>
<hr>
<br>
<form name="post" action="<?= admin_url('admin-post.php') ?>" method="post" id="wp-gatsby-tghp">
    <p class="submit">
        <input type="hidden" name="action" value="<?= TriggerBuild::ACTION_CODE ?>">
        <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?= wp_create_nonce(TriggerBuild::ACTION_CODE) ?>">
        <input type="submit" name="save" id="save-post" class="button button-primary" value="Trigger Build">
        &nbsp;
        <a href="<?= admin_url('tools.php?page=wpgatsbytghp-events') ?>" class="button button-secondary">See All Events</a>
        <br class="clear">
    </p>
</form>