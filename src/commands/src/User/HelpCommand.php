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
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Yii;

/**
 * User "/help" command
 */
class HelpCommand extends BaseUserCommand
{
    /**
     * @var string
     */
    protected $name = 'help';

    /**
     * @var string
     */
    protected $description = 'Show bot commands help';

    /**
     * @var string
     */
    protected $usage = '/help or /help <command>';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    public function __construct(Telegram $telegram, Update $update = null)
    {
        $this->description = Yii::t('app', 'Показывает доступные команды, и их описание');
        $this->usage = Yii::t('app', '/help или /help <команда>');

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

        $message_id = $message->getMessageId();
        $command    = trim($message->getText(true));

        //Only get enabled Admin and User commands
        /** @var Command[] $command_objs */
        $command_objs = array_filter($this->telegram->getCommandsList(), function ($command_obj) {
            /** @var Command $command_obj */
            return !$command_obj->isSystemCommand() && $command_obj->isEnabled();
        });

        //If no command parameter is passed, show the list
        if ($command === '') {
            $text = sprintf(
                '%s '.PHP_EOL.
                Yii::t('app', 'Список команд:') . PHP_EOL,
                $this->telegram->getBotUsername()
            );

            foreach ($command_objs as $command) {
                $text .= sprintf(
                    '/%s - %s' . PHP_EOL,
                    $command->getName(),
                    $command->getDescription()
                );
            }

            $text .= PHP_EOL . Yii::t('app', 'Для того, чтобы узнать подробнее о команде, напишите /help <команда>');
        }else{
            $command = str_replace('/', '', $command);

            if(array_key_exists($command, $command_objs)){
                $command_obj = $command_objs[$command];
                $text = Yii::t('app', 'Команда: {command}{br}Описание: {description}{br}Использование: {usage}', [
                    'command'       =>  $command_obj->getName(),
                    'description'   =>  $command_obj->getDescription(),
                    'usage'         =>  $command_obj->getUsage(),
                    'br'            =>  PHP_EOL
                ]);
            }else{
                $text = Yii::t('app', 'Нет подсказок для команды {command}, так как команда не найдена', [
                    'command'   =>  $command
                ]);
            }
        }

        $data = [
            'chat_id'             => $chat_id,
            'reply_to_message_id' => $message_id,
            'text'                => $text,
        ];

        return Request::sendMessage($data);
    }
}
