<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 27.09.2018
 * Time: 0:05
 */

namespace bobroid\memesRedirectorBot\console;

use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use bobroid\memesRedirectorBot\helpers\DateTimeHelper;
use bobroid\memesRedirectorBot\helpers\TelegramHelper;
use bobroid\memesRedirectorBot\models\Message;
use yii\console\Controller;

class MessagesController extends Controller
{

    public function actionSend()
    {
        $currentTimestamp = strtotime(date('Y-m-d H:i:').'00');
        $lastUpdate = ConfigurationHelper::getLastUpdate();
        $delay = ConfigurationHelper::getDelay();
        $chatId = ConfigurationHelper::getChatId();
        $isSilent = DateTimeHelper::nowIsNight();

        if ($isSilent) {
            $delay = ConfigurationHelper::getNightDelay();
        }

        if ($lastUpdate !== null && $lastUpdate + $delay > $currentTimestamp) {
            return;
        }

        $message = Message::find()->where(['isSent' => 0])->orderBy('created ASC')->one();

        if (empty($message)) {
            return;
        }

        /**
         * @var $message Message
         */

        $data = array_merge([
            'chat_id' => $chatId,
            'disable_notification' => $isSilent ? 'true' : 'false'
        ], $message->getTelegramData());

        if (TelegramHelper::sendRequest($message->getTelegramMethod(), $data)) {
            $message->isSent = 1;
            $message->save();

            ConfigurationHelper::setLastUpdate($currentTimestamp);
        }

        return;
    }
}