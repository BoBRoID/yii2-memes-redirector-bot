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
use bobroid\memesRedirectorBot\models\PinnedMessage;
use Longman\TelegramBot\Exception\TelegramException;
use yii\console\Controller;

class MessagesController extends Controller
{

    /**
     * @throws TelegramException
     */
    public function actionRun()
    {
        $this->actionSend();
        $this->actionUnpin();
        $this->actionPin();
    }

    /**
     * @throws TelegramException
     */
    public function actionSend()
    {
        $currentTimestamp = strtotime(date('Y-m-d H:i:').'00');
        $lastUpdate = ConfigurationHelper::getLastUpdate();
        $delay = ConfigurationHelper::getDelay();
        $chatId = ConfigurationHelper::getChannelId();
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

        if ($response = TelegramHelper::sendRequest($message->getTelegramMethod(), $data)) {
            $message->isSent = 1;
            $message->postedMessageId = $response && $response->result && $response->result->message_id ? $response->result->message_id : null;
            $message->save();

            ConfigurationHelper::setLastUpdate($currentTimestamp);
        }

        $messagesLeft = $message = Message::getCountOfNotSent();

        if ($messagesLeft === 0) {
            foreach (ConfigurationHelper::getAdminsIDs() as $adminID) {
                TelegramHelper::sendMessage([
                    'chat_id'   =>  $adminID,
                    'text'      =>  'Только что в канал был отправлен последний пост.'
                ]);
            }
        }

        return;
    }

    public function actionPin()
    {
        $pinnedMessage = PinnedMessage::findAvailable()->one();

        if (empty($pinnedMessage)) {
            return;
        }

        /**
         * @var $pinnedMessage PinnedMessage
         */

        $chatId = ConfigurationHelper::getChannelId();
        $messageIsPinned = TelegramHelper::pinChatMessage([
            'chat_id'       =>  $chatId,
            'message_id'    =>  $pinnedMessage->message->postedMessageId
        ]);

        if ($messageIsPinned) {
            $pinnedMessage->pinnedAt = time();

            if ($pinnedMessage->save(false)) {
                $notUnpinnedMessages = PinnedMessage::findNotUnpinned()->with(['message'])->all();

                if (!empty($notUnpinnedMessages)) {
                    foreach ($notUnpinnedMessages as $notUnpinnedMessage) {
                        /**
                         * @var PinnedMessage $notUnpinnedMessage
                         */

                        $notUnpinnedMessage->unpinnedAt = time();

                        if ($notUnpinnedMessage->save(false) && $notUnpinnedMessage->removeAfterUnpin) {
                            TelegramHelper::deleteMessage(['chat_id' => $chatId, 'message_id' => $notUnpinnedMessage->message->postedMessageId]);
                        }
                    }
                }
            }
        }

        return;
    }

    public function actionUnpin()
    {
        $date = date('Y-m-d H:i:s');

        $pinnedMessage = PinnedMessage::findNotUnpinned()->where(['<=', 'pinTo', $date])->one();

        if (empty($pinnedMessage)) {
            return;
        }

        /**
         * @var $pinnedMessage PinnedMessage
         */

        $chatId = ConfigurationHelper::getChannelId();
        $messageIsUnpinned = TelegramHelper::unpinChatMessage(['chat_id' => $chatId]);

        if ($messageIsUnpinned) {
            $pinnedMessage->unpinnedAt = time();

            if ($pinnedMessage->save(false) && $pinnedMessage->removeAfterUnpin) {
                TelegramHelper::deleteMessage(['chat_id' => $chatId, 'message_id' => $pinnedMessage->message->postedMessageId]);
            }
        }
    }
}