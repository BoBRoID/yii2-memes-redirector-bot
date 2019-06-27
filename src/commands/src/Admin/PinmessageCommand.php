<?php


namespace Longman\TelegramBot\Commands\AdminCommands;

use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use bobroid\memesRedirectorBot\models\Message;
use bobroid\memesRedirectorBot\models\PinnedMessage;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use yii\validators\DateValidator;

class PinmessageCommand extends BaseAdminCommand
{

    /**
     * @var string
     */
    protected $name = 'pinMessage';

    /**
     * @var string
     */
    protected $description = 'Закрепить сообщение в канале. Дата должна быть в формате Y-m-d H:i! (например 2012-12-21 12:34)';

    /**
     * @var string
     */
    protected $usage = '/pinMessage <дата окончания закрепления сообщения>|<дата начала закрепления сообщения>+<1 если хочешь чтобы пост был удалён после снятия>';

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

        $replyMessage = $message->getReplyToMessage();

        if (empty($replyMessage)) {
            return Request::sendMessage([
                'chat_id'               =>  $chat_id,
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Нужно прислать в ответе на сообщение которое хочешь закрепить!')
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

        // так надо, ибо $deleteAfterUnpin можно не передавать
        @([$dates, $deleteAfterUnpin] = explode('+', $messageText));

        $dates = explode('|', $dates);
        $deleteAfterUnpin = (bool)$deleteAfterUnpin;

        if (empty($dates)) {
            return Request::sendMessage([
                'chat_id'               =>  $chat_id,
                'reply_to_message_id'   =>  $message->getMessageId(),
                'text'                  =>  \Yii::t('tg-posts-redirector', 'Похоже на то что вы забыли указать дату и время! Укажите дату и время в формате Y-m-d H:i (2012-12-21 12:34)!')
            ]);
        }

        $validator = new DateValidator(['format' => 'php:Y-m-d H:i']);

        foreach ($dates as $date) {
            if ($validator->validate($date) === false) {
                return Request::sendMessage([
                    'chat_id'               =>  $chat_id,
                    'reply_to_message_id'   =>  $message->getMessageId(),
                    'text'                  =>  \Yii::t('tg-posts-redirector', 'Значение `{value}` не является допустимой датой в формате Y-m-d H:i (2012-12-21 12:34)!', ['value' => $date])
                ]);
            }
        }

        $pinnedMessage = new PinnedMessage([
            'pinFrom'           =>  $dates[1] ?? null,
            'pinTo'             =>  $dates[0],
            'removeAfterUnpin'  =>  $deleteAfterUnpin
        ]);

        $messageParts = [\Yii::t('tg-posts-redirector', 'Сообщение успешно закреплено')];

        if ($pinnedMessage->pinFrom) {
            $messageParts[] = \Yii::t('tg-posts-redirector', 'с {dateFrom}', ['dateFrom' => \Yii::$app->formatter->asDatetime($pinnedMessage->pinFrom)]);
        }

        if ($pinnedMessage->pinTo) {
            $messageParts[] = \Yii::t('tg-posts-redirector', 'до {dateTo}', ['dateTo' => \Yii::$app->formatter->asDatetime($pinnedMessage->pinTo)]);
        }

        if ($pinnedMessage->removeAfterUnpin) {
            $messageParts[] = \Yii::t('tg-posts-redirector', 'и после этого будет удалено');
        }

        $dbMessage->link('pins', $pinnedMessage);

        return Request::sendMessage([
            'chat_id'               =>  $chat_id,
            'reply_to_message_id'   =>  $replyMessage->getMessageId(),
            'text'                  =>  implode(' ', $messageParts)
        ]);
    }

}