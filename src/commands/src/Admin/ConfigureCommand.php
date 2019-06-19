<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\AdminCommands;

use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class ConfigureCommand extends BaseAdminCommand
{
    /**
     * @var string
     */
    protected $name = 'configure';

    /**
     * @var string
     */
    protected $description = 'Настройки бота';

    /**
     * @var string
     */
    protected $usage = '/configure';

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
        $possibleVars = ConfigurationHelper::getChangeableVars();
        $messageText = $message->getText(true);

        if (empty($messageText)) {
            $varsList = implode(', ', $possibleVars);

            return Request::sendMessage([
                'chat_id' => $chat_id,
                'text'    => "Список доступных переменных для конфигурации: \r\n{$varsList}",
            ]);
        }

        $params = explode(' ', $messageText);

        $data = [
            'chat_id'               =>  $chat_id,
            'reply_to_message_id'   =>  $message->getMessageId(),
            'parse_mode'            =>  'Markdown'
        ];

        if (sizeof($params) === 1) {
            return Request::sendMessage($data + ['text'  =>  "Получена переменная `{$params[0]}`, но аргумент куда-то потерялся. Попробуйте ещё раз."]);
        }

        $varName = array_shift($params);

        if (!in_array($varName, $possibleVars)) {
            return Request::sendMessage($data + ['text' => "Получена переменная `{$varName}`, но её не существует или нельзя редактировать. Попробуйте ещё раз."]);
        }

        $value = array_shift($params);

        if (!ConfigurationHelper::set($varName, $value)) {
            $data['text'] = "Не удалось установить переменной `{$varName}` значение `{$value}`!";
        } else {
            $data['text'] = "Переменной `{$varName}` успешно установлено значение `{$value}`!";
        }

        return Request::sendMessage($data);
    }
}
