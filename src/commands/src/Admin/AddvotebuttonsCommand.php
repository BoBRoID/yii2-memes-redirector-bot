<?php

namespace Longman\TelegramBot\Commands\AdminCommands;

use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class AddvotebuttonsCommand extends BaseAdminCommand
{

    /**
     * @var string
     */
    protected $name = 'addVoteButtons';

    /**
     * @var string
     */
    protected $description = 'Добавить кнопки "лайк" и "дизлайк"';

    /**
     * @var string
     */
    protected $usage = '/addVoteButtons';

    /**
     * @var string
     */
    protected $version = '1.0';

    /**
     * Command execute method
     *
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
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Нужно прислать в ответе на сообщение к которому хочешь прицепить кнопки!')
            ]);
        }

        $dbMessage = Message::findOne(['messageId' => $replyMessage->getMessageId()]);

        if (!$dbMessage) {
            return Request::sendMessage([
                'chat_id'               =>  $chat_id,
                'reply_to_message_id'   =>  $replyMessage->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Сообщение не найдено в базе!')
            ]);
        }


        $dbMessage->useKeyboardId = $dbMessage::KEYBOARD_ID_DISLIKE;
        $dbMessage->save(false);

        if ($dbMessage->isSent) {
            Request::editMessageReplyMarkup([
                'message_id'    =>  $dbMessage->postedMessageId,
                'chat_id'       =>  ConfigurationHelper::getChannelId(),
                'reply_markup'  =>  $dbMessage->getUsingKeyboard()
            ]);
        }

        return Request::sendMessage([
            'chat_id'       =>  $replyMessage->getMessageId(),
            'text'          =>  \Yii::t('tg-posts-redirector', 'Сообщению успешно добавлены кнопки голосования!')
        ]);
    }

}