<?php

namespace Longman\TelegramBot\Commands\AdminCommands;

use bobroid\memesRedirectorBot\helpers\KeyboardHelper;
use bobroid\memesRedirectorBot\keyboards\InlineKeyboardList;
use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class AddlikebuttonCommand extends AdminCommand
{

    /**
     * @var string
     */
    protected $name = 'addLikeButton';

    /**
     * @var string
     */
    protected $description = 'Добавить кнопку "лайк"';

    /**
     * @var string
     */
    protected $usage = '/addLikeButton';

    /**
     * @var string
     */
    protected $version = '1.0';

    /**
     * Command execute method
     *
     * @return ServerResponse
     * @throws TelegramException
     * @throws \yii\base\InvalidConfigException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        \Yii::debug($message);

        $dbMessage = Message::findOne(['messageId' => $message->getMessageId()]);

        if (!$dbMessage) {
            return Request::sendMessage([
                'chat_id'               =>  $chat_id,
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Сообщение не найдено в базе!')
            ]);
        }

        if (!$dbMessage->isSent) {
            $dbMessage->useKeyboardId = $dbMessage::KEYBOARD_ID_LIKE;
            $dbMessage->save(false);
        } else {
            Request::editMessageReplyMarkup([
                'message_id'    =>  $message->getMessageId(),
                'reply_markup'  =>  new InlineKeyboardList([KeyboardHelper::getLikeButton($dbMessage->id)])
            ]);
        }

        return Request::sendMessage([
            'chat_id'       =>  $chat_id,
            'text'          =>  \Yii::t('tg-posts-redirector', 'Посту успешно добавлена клавиатура!')
        ]);
    }

}