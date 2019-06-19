<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use bobroid\memesRedirectorBot\commands\BaseSystemCommand;
use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
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

        if (in_array($chat_id, ConfigurationHelper::getAdminsIDs())) {
            if (!ConfigurationHelper::getChannelId()) {
                return Request::sendMessage([
                    'chat_id'       =>  $chat_id,
                    'text'          =>  'Для бота не установлен канал в который будут падать посты! Установите канал используя команду `/configure channelId <id чата>`!',
                    'parse_mode'    =>  'Markdown'
                ]);
            }
        }

        $data = [
            'chat_id'   =>  $chat_id,
            'text'      =>  'I\'m resend memases to my channel',
            'parse_mode'=>  'Markdown'
        ];

        return Request::sendMessage($data);
    }
}
