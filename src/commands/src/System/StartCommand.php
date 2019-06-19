<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use bobroid\memesRedirectorBot\commands\BaseSystemCommand;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

/**
 * Start command
 */
class StartCommand extends BaseSystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws TelegramException
     */
    public function execute(){
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id'   =>  $chat_id,
            'text'      =>  'I\'m resend memases to my channel',
            'parse_mode'=>  'Markdown'
        ];

        return Request::sendMessage($data);
    }
}
