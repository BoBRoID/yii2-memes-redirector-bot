<?php


namespace Longman\TelegramBot\Commands\AdminCommands;

use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use bobroid\memesRedirectorBot\helpers\TelegramHelper;
use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class SendnowCommand extends BaseAdminCommand
{

    protected $name = 'sendNow';

    protected $description = 'Отправить сообщение в чат сейчас';

    protected $usage = '/sendNow';

    protected $version = '1.0';

    /**
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $replyMessage = $message->getReplyToMessage();

        if (empty($replyMessage)) {
            return Request::sendMessage([
                'chat_id'               =>  $chat_id,
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Нужно прислать в ответе на сообщение которое хочешь отправить вне очереди!')
            ]);
        }

        $dbMessage = Message::findOne(['messageId' => $replyMessage->getMessageId()]);

        if (!$dbMessage) {
            return Request::sendMessage([
                'chat_id'               =>  $replyMessage->getMessageId(),
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Сообщение не найдено в базе!')
            ]);
        }

        if ($dbMessage->isSent) {
            return Request::sendMessage([
                'chat_id'               =>  $replyMessage->getMessageId(),
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Сообщение уже было отправлено!')
            ]);
        }

        if ($response = TelegramHelper::sendRequest($dbMessage->getTelegramMethod(), ['chat_id' => ConfigurationHelper::getChannelId()])) {
            $dbMessage->isSent = 1;
            $dbMessage->postedMessageId = $response && $response->result && $response->result->message_id ? $response->result->message_id : null;
            $dbMessage->save();

            ConfigurationHelper::setLastUpdate(time());
        }

        return Request::sendMessage([
            'chat_id'               =>  $chat_id,
            'text'                  =>  \Yii::t('tg-posts-redirector', 'Сообщение успешно отправлено вне очереди!')
        ]);
    }

}