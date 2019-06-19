<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 26.09.2018
 * Time: 23:53
 */

namespace Longman\TelegramBot\Commands\SystemCommands;


use bobroid\memesRedirectorBot\commands\BaseSystemCommand;
use bobroid\memesRedirectorBot\helpers\MessageHelper;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends BaseSystemCommand
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

    /**
     * Execution if MySQL is required but not available
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chatId = $message->getChat()->getId();

        $dbMessage = MessageHelper::parseToDbMessage($message);

        if ($dbMessage->save() === false) {
            return Request::sendMessage([
                'chat_id'   =>  $chatId,
                'text'      =>  'Пацан к успеху шёл, но не получилось, не фартонуло :('
            ]);
        }

        return Request::emptyResponse();
    }

}