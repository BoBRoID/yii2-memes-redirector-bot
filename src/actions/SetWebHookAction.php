<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 26.09.2018
 * Time: 23:23
 */

namespace bobroid\memesRedirectorBot\actions;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use yii\base\Action;
use yii\helpers\Url;

class SetWebHookAction extends Action
{

    public function run(){
        try{
            $telegram = new Telegram(\Yii::$app->params['apiKey'], \Yii::$app->params['botName']);
            $telegram->deleteWebhook();
            $result = $telegram->setWebhook(Url::to(['/memesRedirectorBot/default/get-hook'], true), [
                'max_connections'   =>  '10'
            ]);

            if($result->isOk()){
                echo $result->getDescription();
            }
        }catch (TelegramException $e){
            echo $e;
        }
    }

}