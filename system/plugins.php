<?php
$app['event']->on('after.system.init', '*', function () use ($app) {
    if ($app['section'] == 'admin' && !$app['user']->isEditor()) {
        $_SESSION['entry'] = $app['request']->url;
        throw new App\Exception\RedirectException('login');
    }
});

$app['event']->on('before.render', 'admin/*', function () use ($app) {
    if (!$app['config']['app']['check_updates']) {
        return;
    }

    $app['cache']->remember('update_checked', function () use ($app) {
        $need = getContentByUrl(API_HOST.'update?version='.VERSION.'&site='.md5($_SERVER['HTTP_HOST']));
        $updateText = 0;
        if ($need == 1) {
            $updateText = t('common_update_cms');
        } elseif ($need == 2) {
            $updateText = t('security_update_cms');
        }
        $app['cache']->put('update_text', $updateText, 600);
        return 1;
    }, 86400);

    $update_text = $app['cache']->get('update_text');
    if ($update_text) {
        alert($update_text);
    }
});
