<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 26.09.2018
 * Time: 23:20
 */

namespace bobroid\memesRedirectorBot\actions;

use bobroid\memesRedirectorBot\models\Configuration;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use yii\base\Action;

class GetWebHookAction extends Action
{

    public function run(){
        try{
            $telegram = new Telegram(\Yii::$app->params['apiKey'], \Yii::$app->params['botName']);

            \Yii::debug(\Yii::getAlias('@vendor/bobroid/memesRedirectorBot/src/commands/src'));

            $telegram->addCommandsPath(\Yii::getAlias('@vendor/bobroid/memesRedirectorBot/src/commands/src'));
            $telegram->enableAdmins(Configuration::getAdminsIDs());
            $telegram->handle();
        }catch (TelegramException $e){
            \Yii::error($e->getMessage());
        }
    }

}