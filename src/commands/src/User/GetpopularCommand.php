<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use bobroid\memesRedirectorBot\commands\BaseUserCommand;
use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Yii;

/**
 * User "/help" command
 */
class GetpopularCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'getPopular';

    /**
     * @var string
     */
    protected $description = 'sending most popular post for the time';

    /**
     * @var string
     */
    protected $usage = '/getPopular or /getPopular <days>';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    public function __construct(Telegram $telegram, Update $update = null)
    {
        $this->description = Yii::t('tg-posts-redirector', 'Отправляет самый популярный пост (по умолчанию за последние 24 часа)');
        $this->usage = Yii::t('tg-posts-redirector', '/getPopular или /getPopular <дней>');

        parent::__construct($telegram, $update);
    }

    /**
     * Command execute method
     *
     * @return mixed
     * @throws TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $daysCount = (int)trim($message->getText(true));

        if (empty($daysCount) || $daysCount < 1) {
            $daysCount = 1;
        }

        $dateStart = strtotime("-{$daysCount} days");

        $dbMessage = Message::find()
            ->andWhere(['>=', 'hasBeenSentAt', $dateStart])
            ->andWhere(['isSent' => 1])
            ->orderBy('viewsCount DESC')
            ->one();

        if (empty($dbMessage)) {
            return Request::sendMessage([
                'chat_id'   =>  $chat_id,
                'text'      =>  \Yii::t('tg-posts-redirector', 'Не найдено ни одного сообщения которое подходило-бы под ваш запрос')
            ]);
        }

        /**
         * @var $dbMessage Message
         */

        return Request::forwardMessage([
            'chat_id'       =>  $chat_id,
            'from_chat_id'  =>  ConfigurationHelper::getChannelId(),
            'message_id'    =>  $dbMessage->postedMessageId
        ]);
    }
}
