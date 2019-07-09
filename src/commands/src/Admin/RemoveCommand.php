<?php


namespace Longman\TelegramBot\Commands\AdminCommands;

use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use bobroid\memesRedirectorBot\helpers\TelegramHelper;
use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Throwable;
use yii\db\StaleObjectException;

class RemoveCommand extends BaseAdminCommand
{

    protected $name = 'remove';

    protected $description = 'Удалить сообщение из очереди';

    protected $usage = '/sendNow';

    protected $version = '1.0';

    /**
     * @return ServerResponse
     * @throws TelegramException
     * @throws Throwable
     * @throws StaleObjectException
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
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Нужно прислать в ответе на сообщение которое хочешь удалить!')
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

        if ($dbMessage->delete() === false) {
            return Request::sendMessage([
                'chat_id'               =>  $replyMessage->getMessageId(),
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'По каким-то одному богу ведомым причинам не удалось удалить сообщение!')
            ]);
        }

        return Request::sendMessage([
            'chat_id'               =>  $chat_id,
            'text'                  =>  \Yii::t('tg-posts-redirector', 'Сообщение успешно удалено из очереди!')
        ]);
    }

}