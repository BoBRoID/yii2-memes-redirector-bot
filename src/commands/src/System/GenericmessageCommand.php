<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 26.09.2018
 * Time: 23:53
 */

namespace Longman\TelegramBot\Commands\SystemCommands;


use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use bobroid\memesRedirectorBot\helpers\MessageHelper;
use bobroid\memesRedirectorBot\helpers\TelegramHelper;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends SystemCommand
{

    /**
     * @var string
     */
    protected $name = 'Genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.0';

    private $telegramUpdatesUserId = 777000;

    /**
     * Execution if MySQL is required but not available
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat = $message->getChat();
        $chatId = $chat->getId();

        if ($chat->isPrivateChat()) {
            if (!in_array($chatId, ConfigurationHelper::getAdminsIDs(), true)) {
                return Request::sendMessage([
                    'chat_id'   =>  $chatId,
                    'text'      =>  'I work just from my creator, so sorry'
                ]);
            }

            $dbMessage = MessageHelper::parseToDbMessage($message);

            if ($dbMessage->save() === false) {
                return Request::sendMessage([
                    'chat_id'   =>  $chatId,
                    'text'      =>  'Пацан к успеху шёл, но не получилось, не фартонуло :('
                ]);
            }
        }

        if ($message->getFrom()->getId() === $this->telegramUpdatesUserId) {
            TelegramHelper::deleteMessage(['chat_id' => $chatId, 'message_id' => $message->getMessageId()]);
        }

        return Request::emptyResponse();
    }

}