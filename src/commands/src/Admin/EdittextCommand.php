<?php


namespace Longman\TelegramBot\Commands\AdminCommands;

use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use bobroid\memesRedirectorBot\helpers\TelegramHelper;
use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class EdittextCommand extends BaseAdminCommand
{

    protected $name = 'editText';

    protected $description = 'Изменить текст сообщения';

    protected $usage = '/editText';

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
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Нужно прислать в ответе на сообщение которое хочешь отредактировать!')
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

        $messageText = $message->getText(true);

        $dbMessage->text = $messageText ? utf8_encode($messageText) : null;

        if ($dbMessage->save() === false) {
            return Request::sendMessage([
                'chat_id'               =>  $replyMessage->getMessageId(),
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'По каким-то одному богу ведомым причинам не удалось отредактировать сообщение!')
            ]);
        }

        return Request::sendMessage([
            'chat_id'               =>  $chat_id,
            'text'                  =>  \Yii::t('tg-posts-redirector', 'Сообщение успешно отредактировано!')
        ]);
    }

}