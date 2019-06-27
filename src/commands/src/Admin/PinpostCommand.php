<?php


namespace bobroid\memesRedirectorBot\commands\src\Admin;


use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use yii\validators\DateValidator;

class PinpostCommand extends BaseAdminCommand
{

    /**
     * @var string
     */
    protected $name = 'pinPost';

    /**
     * @var string
     */
    protected $description = 'Закрепить пост в канале. Дата должна быть в формате Y-m-d H:i! (например 01-12-2012 12:34)';

    /**
     * @var string
     */
    protected $usage = '/pinPost <до какого времени> <с какого времени (не обязательно)>';

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
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Нужно прислать в ответе пост к которому хочешь прицепить кнопку!')
            ]);
        }

        $dbMessage = Message::findOne(['messageId' => $replyMessage->getMessageId()]);

        if (!$dbMessage) {
            return Request::sendMessage([
                'chat_id'               =>  $chat_id,
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Сообщение не найдено в базе!')
            ]);
        }

        $messageText = $message->getText(true);
        $params = explode(' ', $messageText);

        if (empty($params)) {
            return Request::sendMessage([
                'chat_id'               =>  $chat_id,
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Похоже на то что вы забыли указать дату и время! Укажите дату и время в формате 01-12-2012 12:34')
            ]);
        }

        $validator = new DateValidator();

        foreach ($params as $date) {
            if ($validator->validate($date) === false) {
                return Request::sendMessage([
                    'chat_id'               =>  $chat_id,
                    'reply_to_message_id'   =>  $message->getMessageId(),
                    'text'                  =>  \Yii::t('tg-posts-redirector', 'Значение {value} не является допустимой датой!', ['value' => $date])
                ]);
            }
        }



    }

}