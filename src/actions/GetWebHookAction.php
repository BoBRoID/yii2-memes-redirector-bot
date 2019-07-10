<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 26.09.2018
 * Time: 23:20
 */

namespace bobroid\memesRedirectorBot\actions;

use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use yii\base\Action;

class GetWebHookAction extends Action
{

    public function run(){
        try{
            $telegram = new Telegram(\Yii::$app->params['apiKey'], \Yii::$app->params['botName']);

            $telegram->addCommandsPath(\Yii::getAlias('@vendor/bobroid/yii2-memes-redirector-bot/src/commands/src'));
            $telegram->enableAdmins(ConfigurationHelper::getAdminsIDs());
            $telegram->handle();
        }catch (TelegramException $e){
            \Yii::error($e->getMessage());
        }
    }

}