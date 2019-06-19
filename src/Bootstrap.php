<?php


namespace bobroid\memesRedirectorBot;


use yii\base\Application;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if (!$webHookUrlHash = $app->cache->get('webHookUrl')) {
            $webHookUrlHash = md5(time().mktime());
            $app->cache->set('webHookUrl', $webHookUrlHash);
        }

        $app->urlManager->addRules([
            '/bot/web-hook/set'                     =>  'myrbot/default/set-hook',
            "/bot/web-hook/get-{$webHookUrlHash}"   =>  'myrbot/default/get-hook'
        ]);

        $app->setModule('myrbot', Module::class);
    }
}